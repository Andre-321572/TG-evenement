import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, ActivityIndicator, TouchableOpacity, RefreshControl, ScrollView } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function ScanStatsScreen() {
  const [statsCount, setStatsCount] = useState(0);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      const response = await apiClient.get('/scanner/stats');
      if (response.data.status === 'success') {
        setStatsCount(response.data.count);
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
    fetchStats();
  };

  if (isLoading && !refreshing) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#2563eb" />
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      contentContainerStyle={styles.content}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#2563eb" />
      }
    >
      <View style={styles.card}>
        <Ionicons name="scan-circle-outline" size={64} color="#2563eb" style={{ marginBottom: 12 }} />
        <Text style={styles.title}>Scans d'Aujourd'hui</Text>
        <Text style={styles.number}>{statsCount}</Text>
        <Text style={styles.description}>Billets validés et enregistrés avec succès aujourd'hui.</Text>
      </View>

      <TouchableOpacity style={styles.refreshButton} onPress={fetchStats}>
        <Text style={styles.refreshButtonText}>Actualiser les Statistiques</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  content: {
    padding: 24,
    justifyContent: 'center',
    flexGrow: 1,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 20,
    padding: 32,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.02,
    shadowRadius: 8,
    elevation: 2,
    marginBottom: 24,
  },
  title: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#64748b',
    marginBottom: 16,
    textTransform: 'uppercase',
    letterSpacing: 1,
  },
  number: {
    fontSize: 72,
    fontWeight: 'bold',
    color: '#2563eb',
    marginBottom: 16,
  },
  description: {
    fontSize: 14,
    color: '#475569',
    textAlign: 'center',
    lineHeight: 20,
  },
  refreshButton: {
    backgroundColor: '#2563eb',
    borderRadius: 12,
    paddingVertical: 14,
    alignItems: 'center',
    shadowColor: '#2563eb',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 8,
    elevation: 3,
  },
  refreshButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
