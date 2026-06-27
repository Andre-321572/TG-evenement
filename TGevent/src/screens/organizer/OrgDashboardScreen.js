import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, ActivityIndicator, TouchableOpacity, RefreshControl, Alert, Image } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function OrgDashboardScreen({ navigation }) {
  const [stats, setStats] = useState(null);
  const [events, setEvents] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      const response = await apiClient.get('/organisateur/dashboard');
      if (response.data.status === 'success') {
        setStats(response.data.statistics);
        setEvents(response.data.events);
      }
    } catch (e) {
      console.error(e);
      Alert.alert('Erreur', 'Impossible de charger les données du dashboard.');
    } finally {
      setIsLoading(false);
      setRefreshing(false);
    }
  };

  const handlePublishToggle = async (event) => {
    const action = event.statut === 'publier' ? 'archive' : 'publish';
    const actionLabel = event.statut === 'publier' ? 'remettre en brouillon' : 'publier';

    Alert.alert(
      'Confirmer',
      `Voulez-vous vraiment ${actionLabel} cet événement ?`,
      [
        { text: 'Annuler', style: 'cancel' },
        {
          text: 'Confirmer',
          onPress: async () => {
            try {
              const response = await apiClient.post(`/organisateur/events/${event.id}/${action}`);
              if (response.data.status === 'success') {
                Alert.alert('Succès', `Événement ${event.statut === 'publier' ? 'mis en brouillon' : 'publié'} avec succès.`);
                fetchDashboardData();
              }
            } catch (e) {
              console.error(e);
              Alert.alert('Erreur', 'Une erreur est survenue.');
            }
          },
        },
      ]
    );
  };

  const handleDeleteEvent = (eventId) => {
    Alert.alert(
      'Supprimer',
      'Voulez-vous vraiment supprimer cet événement définitivement ?',
      [
        { text: 'Annuler', style: 'cancel' },
        {
          text: 'Supprimer',
          style: 'destructive',
          onPress: async () => {
            try {
              const response = await apiClient.delete(`/organisateur/events/${eventId}`);
              if (response.data.status === 'success') {
                Alert.alert('Succès', 'Événement supprimé.');
                fetchDashboardData();
              }
            } catch (e) {
              console.error(e);
              Alert.alert('Erreur', 'Impossible de supprimer l\'événement.');
            }
          },
        },
      ]
    );
  };

  const handleRefresh = () => {
    setRefreshing(true);
    fetchDashboardData();
  };

  const renderEventItem = ({ item }) => (
    <View style={styles.eventCard}>
      <Image source={{ uri: item.photo_url }} style={styles.eventImage} />
      <View style={styles.eventBody}>
        <Text style={styles.eventTitle}>{item.titre}</Text>
        <Text style={styles.eventInfo}>📅 {item.date} - 📍 {item.lieu}</Text>
        <View style={styles.statusRow}>
          <Text
            style={[
              styles.statusText,
              item.statut === 'publier' ? styles.statusPublie : styles.statusBrouillon,
            ]}
          >
            {item.statut === 'publier' ? 'En ligne / Publié' : 'Brouillon'}
          </Text>
        </View>

        <View style={styles.actionsRow}>
          <TouchableOpacity
            style={[styles.actionBtn, styles.toggleBtn]}
            onPress={() => handlePublishToggle(item)}
          >
            <Text style={styles.actionBtnText}>
              {item.statut === 'publier' ? 'Masquer' : 'Publier'}
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[styles.actionBtn, styles.deleteBtn]}
            onPress={() => handleDeleteEvent(item.id)}
          >
            <Text style={styles.actionBtnText}>Supprimer</Text>
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );

  const HeaderComponent = () => (
    <View style={styles.header}>
      {stats && (
        <View style={styles.statsContainer}>
          <View style={styles.statsCard}>
            <Text style={styles.statsLabel}>Total</Text>
            <Text style={[styles.statsNumber, { color: '#f59e0b' }]}>{stats.total_events}</Text>
          </View>
          <View style={styles.statsCard}>
            <Text style={styles.statsLabel}>Publiés</Text>
            <Text style={[styles.statsNumber, { color: '#10b981' }]}>{stats.published_events}</Text>
          </View>
          <View style={styles.statsCard}>
            <Text style={styles.statsLabel}>Brouillons</Text>
            <Text style={[styles.statsNumber, { color: '#64748b' }]}>{stats.draft_events}</Text>
          </View>
        </View>
      )}
      <Text style={styles.listTitle}>Mes Événements</Text>
    </View>
  );

  if (isLoading && !refreshing) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#f59e0b" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={events}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderEventItem}
        ListHeaderComponent={HeaderComponent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#f59e0b" />
        }
        contentContainerStyle={{ paddingBottom: 24 }}
        ListEmptyComponent={
          <Text style={styles.emptyText}>Vous n'avez pas encore créé d'événement.</Text>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
  },
  header: {
    padding: 16,
  },
  statsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 24,
  },
  statsCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    width: '30%',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.01,
    shadowRadius: 4,
    elevation: 1,
  },
  statsLabel: {
    color: '#64748b',
    fontSize: 11,
    fontWeight: '600',
    textTransform: 'uppercase',
  },
  statsNumber: {
    fontSize: 24,
    fontWeight: 'bold',
    marginTop: 8,
  },
  listTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginTop: 10,
  },
  eventCard: {
    backgroundColor: '#fff',
    borderRadius: 16,
    marginHorizontal: 16,
    marginBottom: 16,
    flexDirection: 'row',
    overflow: 'hidden',
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 4,
    elevation: 2,
  },
  eventImage: {
    width: 100,
    height: '100%',
    minHeight: 120,
  },
  eventBody: {
    flex: 1,
    padding: 12,
  },
  eventTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 4,
  },
  eventInfo: {
    color: '#64748b',
    fontSize: 12,
    marginBottom: 6,
  },
  statusRow: {
    marginBottom: 12,
  },
  statusText: {
    fontSize: 11,
    fontWeight: 'bold',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 6,
    alignSelf: 'flex-start',
    overflow: 'hidden',
  },
  statusPublie: {
    color: '#047857',
    backgroundColor: '#ecfdf5',
  },
  statusBrouillon: {
    color: '#475569',
    backgroundColor: '#f1f5f9',
  },
  actionsRow: {
    flexDirection: 'row',
  },
  actionBtn: {
    borderRadius: 8,
    paddingHorizontal: 12,
    paddingVertical: 6,
    marginRight: 8,
  },
  toggleBtn: {
    backgroundColor: '#f59e0b',
  },
  deleteBtn: {
    backgroundColor: '#ef4444',
  },
  actionBtnText: {
    color: '#fff',
    fontSize: 11,
    fontWeight: 'bold',
  },
  emptyText: {
    color: '#64748b',
    textAlign: 'center',
    marginTop: 40,
    fontStyle: 'italic',
  },
});
