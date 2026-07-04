import React, { useState, useContext, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, TouchableOpacity, Image, ScrollView, Alert, ActivityIndicator, RefreshControl } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';
import apiClient from '../../api/client';

export default function NotificationsScreen({ navigation }) {
  const { token } = useContext(AuthContext);
  const [selectedFilter, setSelectedFilter] = useState('Toutes'); // 'Toutes', 'Événements', 'Achats', 'Annonces'
  const [notifications, setNotifications] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    if (token) {
      fetchNotifications();
    } else {
      setIsLoading(false);
    }
  }, [token]);

  const fetchNotifications = async () => {
    try {
      const response = await apiClient.get('/notifications');
      if (response.data.status === 'success') {
        setNotifications(response.data.notifications);
      }
    } catch (e) {
      console.error('Erreur chargement notifications', e);
    } finally {
      setIsLoading(false);
      setRefreshing(false);
    }
  };

  const handleRefresh = () => {
    setRefreshing(true);
    fetchNotifications();
  };

  const markAllAsRead = () => {
    const updated = notifications.map(notif => ({ ...notif, isNew: false }));
    setNotifications(updated);
    Alert.alert('Notifications', 'Toutes les notifications ont été marquées comme lues !');
  };

  const filteredNotifications = notifications.filter(notif => {
    if (selectedFilter === 'Toutes') return true;
    return notif.filterType === selectedFilter;
  });

  const renderNotificationItem = ({ item }) => {
    if (item.hasBanner) {
      return (
        <View style={styles.bannerCard}>
          <Image source={{ uri: item.bannerUrl }} style={styles.bannerImage} />
          <View style={styles.bannerOverlay}>
            <View style={styles.exclusivityBadge}>
              <Ionicons name="sparkles" size={12} color="#fff" style={{ marginRight: 4 }} />
              <Text style={styles.exclusivityText}>{item.category}</Text>
            </View>
            <Text style={styles.bannerTitle}>{item.title}</Text>
            <TouchableOpacity 
              style={styles.bannerButton}
              onPress={() => navigation.navigate('Découvrir')}
            >
              <Text style={styles.bannerButtonText}>Voir</Text>
            </TouchableOpacity>
          </View>
        </View>
      );
    }

    return (
      <View style={[styles.notificationCard, item.isNew && styles.newCardBorder]}>
        <View style={[styles.iconContainer, { backgroundColor: item.iconBg || '#f3f4f6' }]}>
          <Ionicons name={item.iconName || 'notifications-outline'} size={22} color={item.iconColor || '#6b7280'} />
        </View>

        <View style={styles.textContainer}>
          <View style={styles.cardHeader}>
            <Text style={[styles.categoryText, { color: item.iconColor || '#6b7280' }]}>{item.category}</Text>
            <Text style={styles.timeText}>{item.time}</Text>
          </View>
          <Text style={styles.cardTitle}>{item.title}</Text>
          <Text style={styles.cardMessage}>{item.message}</Text>
          {item.isNew && (
            <View style={styles.newBadge}>
              <Ionicons name="ellipse" size={8} color="#ef4444" style={{ marginRight: 4 }} />
              <Text style={styles.newBadgeText}>Nouveau</Text>
            </View>
          )}
        </View>
      </View>
    );
  };

  // État invité (non connecté)
  if (!token) {
    return (
      <View style={styles.guestContainer}>
        <View style={styles.guestCard}>
          <View style={styles.guestIconBg}>
            <Ionicons name="notifications-outline" size={48} color="#2563eb" />
          </View>
          <Text style={styles.guestTitle}>Restez informé</Text>
          <Text style={styles.guestSubtitle}>
            Connectez-vous pour recevoir des rappels sur vos événements, des confirmations d'achats et des offres exclusives.
          </Text>
          <TouchableOpacity 
            style={styles.loginButton}
            onPress={() => navigation.navigate('Login')}
          >
            <Text style={styles.loginButtonText} numberOfLines={1}>Se connecter</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  }

  if (isLoading && !refreshing) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#1e3a8a" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Title Header with mark all as read button */}
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Notifications</Text>
        <TouchableOpacity onPress={markAllAsRead}>
          <Text style={styles.markAllRead}>Tout marquer comme lu</Text>
        </TouchableOpacity>
      </View>

      {/* Filter Tabs */}
      <View style={styles.tabsContainer}>
        <ScrollView horizontal showsHorizontalScrollIndicator={false}>
          {['Toutes', 'Événements', 'Achats', 'Annonces'].map((filter) => (
            <TouchableOpacity
              key={filter}
              style={[
                styles.tabButton,
                selectedFilter === filter && styles.tabButtonActive,
              ]}
              onPress={() => setSelectedFilter(filter)}
            >
              <Text
                style={[
                  styles.tabButtonText,
                  selectedFilter === filter && styles.tabButtonTextActive,
                ]}
              >
                {filter}
              </Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      {/* Notifications List */}
      <FlatList
        data={filteredNotifications}
        keyExtractor={(item) => item.id}
        renderItem={renderNotificationItem}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#1e3a8a" />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="notifications-off-outline" size={48} color="#94a3b8" />
            <Text style={styles.emptyText}>Aucune notification</Text>
          </View>
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
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingTop: 20,
    paddingBottom: 12,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#1e3a8a',
  },
  markAllRead: {
    fontSize: 13,
    color: '#f43f5e',
    fontWeight: '600',
  },
  tabsContainer: {
    backgroundColor: '#fff',
    paddingVertical: 12,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#e2e8f0',
  },
  tabButton: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    backgroundColor: '#f1f5f9',
    borderRadius: 99,
    marginRight: 8,
  },
  tabButtonActive: {
    backgroundColor: '#1e3a8a',
  },
  tabButtonText: {
    color: '#475569',
    fontSize: 13,
    fontWeight: '600',
  },
  tabButtonTextActive: {
    color: '#fff',
  },
  listContent: {
    padding: 16,
  },
  notificationCard: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 4,
    elevation: 1,
  },
  newCardBorder: {
    borderLeftWidth: 4,
    borderLeftColor: '#3b82f6',
  },
  iconContainer: {
    width: 44,
    height: 44,
    borderRadius: 22,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  textContainer: {
    flex: 1,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 6,
  },
  categoryText: {
    fontSize: 11,
    fontWeight: 'bold',
    letterSpacing: 0.5,
  },
  timeText: {
    fontSize: 11,
    color: '#94a3b8',
  },
  cardTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#0f172a',
    marginBottom: 4,
    lineHeight: 18,
  },
  cardMessage: {
    fontSize: 13,
    color: '#475569',
    lineHeight: 18,
    marginBottom: 6,
  },
  newBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fef2f2',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 99,
    alignSelf: 'flex-start',
  },
  newBadgeText: {
    color: '#ef4444',
    fontSize: 10,
    fontWeight: 'bold',
  },
  bannerCard: {
    borderRadius: 12,
    overflow: 'hidden',
    marginBottom: 12,
    height: 200,
    position: 'relative',
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  bannerImage: {
    width: '100%',
    height: '100%',
  },
  bannerOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(15, 23, 42, 0.4)',
    padding: 16,
    justifyContent: 'flex-end',
  },
  exclusivityBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ef4444',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 4,
    alignSelf: 'flex-start',
    marginBottom: 8,
  },
  exclusivityText: {
    color: '#fff',
    fontSize: 9,
    fontWeight: 'bold',
  },
  bannerTitle: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 12,
  },
  bannerButton: {
    backgroundColor: '#ef4444',
    paddingHorizontal: 20,
    paddingVertical: 8,
    borderRadius: 6,
    alignSelf: 'flex-start',
  },
  bannerButtonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 13,
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
    textAlign: 'center',
    alignSelf: 'stretch',
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
    textAlign: 'center',
    width: '100%',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
  },
  emptyContainer: {
    padding: 40,
    alignItems: 'center',
    justifyContent: 'center',
  },
  emptyText: {
    color: '#94a3b8',
    fontSize: 15,
    marginTop: 12,
    textAlign: 'center',
  },
});
