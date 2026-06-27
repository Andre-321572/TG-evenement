import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, ActivityIndicator, RefreshControl, Platform } from 'react-native';
import QRCode from 'react-native-qrcode-svg';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function TicketListScreen() {
  const [tickets, setTickets] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchTickets();
  }, []);

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

  // Helper pour formater la date du billet
  const formatTicketDate = (dateString) => {
    if (!dateString) return 'Non planifié';
    try {
      const date = new Date(dateString);
      const months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
      return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    } catch (e) {
      return dateString;
    }
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
          <Text style={[styles.statusText, item.is_scanned ? styles.statusTextScanned : styles.statusTextValid]}>
            {item.is_scanned ? 'Scanné / Utilisé' : 'Valide / Non scanné'}
          </Text>
        </View>
      </View>

      <View style={styles.ticketBody}>
        <View style={styles.qrContainer}>
          <QRCode
            value={item.code}
            size={110}
            color="#1e293b"
            backgroundColor="#ffffff"
          />
        </View>

        <View style={styles.ticketDetails}>
          <Text style={styles.codeLabel}>CODE BILLET</Text>
          <Text style={styles.codeText}>{item.code}</Text>
          <Text style={styles.detailLabel}>TYPE</Text>
          <Text style={styles.detailText}>{item.billet?.type || 'Standard'}</Text>
          <Text style={styles.detailLabel}>📍 LIEU</Text>
          <Text style={styles.detailText} numberOfLines={1}>{item.evenement?.lieu}</Text>
          <Text style={styles.detailLabel}>📅 DATE</Text>
          <Text style={styles.detailText}>{formatTicketDate(item.evenement?.date)}</Text>
        </View>
      </View>
    </View>
  );

  if (isLoading && !refreshing) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#1d4ed8" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {tickets.length === 0 ? (
        <View style={styles.empty}>
          <Ionicons name="ticket-outline" size={60} color="#94a3b8" />
          <Text style={styles.emptyText}>Vous n'avez pas encore acheté de billet.</Text>
        </View>
      ) : (
        <FlatList
          data={tickets}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderTicketItem}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#1d4ed8" />
          }
          contentContainerStyle={styles.listContent}
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
  listContent: {
    padding: 20,
    paddingBottom: 40,
  },
  card: {
    backgroundColor: '#ffffff',
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#f1f5f9',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.04,
    shadowRadius: 8,
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
    color: '#0f172a',
    marginBottom: 8,
  },
  statusBadge: {
    alignSelf: 'flex-start',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 20,
  },
  statusBadgeValid: {
    backgroundColor: '#d1fae5',
  },
  statusBadgeScanned: {
    backgroundColor: '#fee2e2',
  },
  statusText: {
    fontSize: 11,
    fontWeight: 'bold',
  },
  statusTextValid: {
    color: '#065f46',
  },
  statusTextScanned: {
    color: '#991b1b',
  },
  ticketBody: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  qrContainer: {
    backgroundColor: '#ffffff',
    padding: 8,
    borderRadius: 12,
    marginRight: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    alignItems: 'center',
    justifyContent: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 1,
  },
  ticketDetails: {
    flex: 1,
  },
  codeLabel: {
    fontSize: 9,
    color: '#94a3b8',
    fontWeight: 'bold',
    letterSpacing: 0.5,
  },
  codeText: {
    fontSize: 15,
    fontWeight: 'bold',
    color: '#1d4ed8',
    marginBottom: 8,
  },
  detailLabel: {
    fontSize: 9,
    color: '#94a3b8',
    fontWeight: 'bold',
    letterSpacing: 0.5,
  },
  detailText: {
    color: '#334155',
    fontSize: 12,
    fontWeight: '600',
    marginBottom: 6,
  },
  empty: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 40,
  },
  emptyText: {
    color: '#64748b',
    fontSize: 15,
    textAlign: 'center',
    marginTop: 12,
  },
});
