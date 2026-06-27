import React, { useState, useEffect, useContext } from 'react';
import { StyleSheet, Text, View, FlatList, ActivityIndicator, RefreshControl, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import QRCode from 'react-native-qrcode-svg';
import apiClient from '../../api/client';
import { AuthContext } from '../../context/AuthContext';

export default function TicketListScreen({ navigation }) {
  const { token } = useContext(AuthContext);
  const [tickets, setTickets] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    if (token) {
      fetchTickets();
    } else {
      setIsLoading(false);
    }
  }, [token]);

  const fetchTickets = async () => {
    try {
      const response = await apiClient.get('/my-tickets');
      if (response.data.status === 'success') {
        setTickets(response.data.tickets);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setIsLoading(false);
      setRefreshing(false);
    }
  };

  const handleRefresh = () => {
    setRefreshing(true);
    fetchTickets();
  };

  const renderTicketItem = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.ticketHeader}>
        <Text style={styles.eventTitle}>{item.evenement?.titre}</Text>
        <View
          style={[
            styles.statusBadge,
            item.is_scanned ? styles.statusBadgeScanned : styles.statusBadgeValid,
          ]}
        >
          <Text style={styles.statusText}>
            {item.is_scanned ? 'Scanné / Utilisé' : 'Valide / Non scanné'}
          </Text>
        </View>
      </View>

      <View style={styles.ticketBody}>
        <View style={styles.qrContainer}>
          <QRCode
            value={item.code}
            size={110}
            color="#000000"
            backgroundColor="#ffffff"
          />
        </View>

        <View style={styles.ticketDetails}>
          <Text style={styles.codeText}>Code : {item.code}</Text>
          <Text style={styles.detailText}>Type : {item.billet?.type}</Text>
          <Text style={styles.detailText}>📍 Lieu : {item.evenement?.lieu}</Text>
          <Text style={styles.detailText}>📅 Date : {item.evenement?.date}</Text>
        </View>
      </View>
    </View>
  );

  // État invité (non connecté)
  if (!token) {
    return (
      <View style={styles.guestContainer}>
        <View style={styles.guestCard}>
          <View style={styles.guestIconBg}>
            <Ionicons name="ticket-outline" size={48} color="#2563eb" />
          </View>
          <Text style={styles.guestTitle}>Vos Billets</Text>
          <Text style={styles.guestSubtitle}>
            Connectez-vous pour voir vos billets achetés, afficher les codes QR d'accès et entrer dans vos événements.
          </Text>
          <TouchableOpacity 
            style={styles.loginButton}
            onPress={() => navigation.navigate('Login')}
          >
            <Text style={styles.loginButtonText}>Se connecter</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  }

  if (isLoading && !refreshing) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#2563eb" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {tickets.length === 0 ? (
        <View style={styles.empty}>
          <Ionicons name="ticket-outline" size={64} color="#94a3b8" style={{ marginBottom: 12 }} />
          <Text style={styles.emptyText}>Vous n'avez pas encore acheté de billet.</Text>
        </View>
      ) : (
        <FlatList
          data={tickets}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderTicketItem}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#2563eb" />
          }
          contentContainerStyle={{ padding: 16 }}
        />
      )}
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
  card: {
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 4,
    elevation: 2,
  },
  ticketHeader: {
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
    paddingBottom: 12,
    marginBottom: 16,
  },
  eventTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 6,
  },
  statusBadge: {
    alignSelf: 'flex-start',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 99,
  },
  statusBadgeValid: {
    backgroundColor: '#ecfdf5',
  },
  statusBadgeScanned: {
    backgroundColor: '#fef2f2',
  },
  statusText: {
    fontSize: 11,
    fontWeight: 'bold',
    color: '#047857',
  },
  ticketBody: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  qrContainer: {
    backgroundColor: '#fff',
    padding: 8,
    borderRadius: 12,
    marginRight: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  ticketDetails: {
    flex: 1,
  },
  codeText: {
    fontSize: 15,
    fontWeight: 'bold',
    color: '#2563eb',
    marginBottom: 6,
  },
  detailText: {
    color: '#475569',
    fontSize: 13,
    marginBottom: 4,
  },
  empty: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  emptyText: {
    color: '#64748b',
    fontSize: 15,
    textAlign: 'center',
  },
  guestContainer: {
    flex: 1,
    backgroundColor: '#f8fafc',
    justifyContent: 'center',
    padding: 24,
  },
  guestCard: {
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 32,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 4,
  },
  guestIconBg: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#eff6ff',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
  },
  guestTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 10,
  },
  guestSubtitle: {
    fontSize: 14,
    color: '#64748b',
    textAlign: 'center',
    lineHeight: 20,
    marginBottom: 24,
  },
  loginButton: {
    backgroundColor: '#2563eb',
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 8,
    width: '100%',
    alignItems: 'center',
  },
  loginButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
