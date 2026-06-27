import React, { useState } from 'react';
import { StyleSheet, Text, View, FlatList, TouchableOpacity, Image, Platform, ImageBackground } from 'react-native';
import { Ionicons, Feather, MaterialCommunityIcons } from '@expo/vector-icons';

export default function NotificationsScreen() {
  const [selectedFilter, setSelectedFilter] = useState('Toutes');

  const notifications = [
    {
      id: '1',
      type: 'rappel',
      category: 'RAPPEL',
      time: 'Il y a 2h',
      title: 'Votre événement Indigo Nights commence bientôt !',
      text: 'L\'ouverture des portes est prévue dans 2 heures. Préparez votre QR...',
      isNew: true,
      icon: 'notifications',
      iconBg: '#e0e7ff',
      iconColor: '#1d4ed8',
    },
    {
      id: '2',
      type: 'achat',
      category: 'ACHAT',
      time: 'Hier',
      title: 'Confirmation d\'achat : Urban Jazz Festival',
      text: 'Votre paiement de 45,00€ a été validé. Vos billets sont disponibles dans votre profil.',
      isNew: false,
      icon: 'receipt-outline',
      iconBg: '#eff6ff',
      iconColor: '#2563eb',
    },
    {
      id: '3',
      type: 'annonce',
      category: 'EXCLUSIVITÉ',
      title: 'Nouvelle annonce : The Lumineers',
      bgImage: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=600&auto=format&fit=crop',
      isNew: false,
    },
    {
      id: '4',
      type: 'artiste',
      category: 'ARTISTE',
      time: 'Il y a 4h',
      title: 'Nouvelle date pour M83 à Paris',
      text: 'L\'artiste que vous suivez vient d\'ajouter une date au Zénith. Soyez le premier à réserver !',
      isNew: true,
      icon: 'star',
      iconBg: '#fae8ff',
      iconColor: '#d946ef',
    },
    {
      id: '5',
      type: 'systeme',
      category: 'SYSTÈME',
      time: 'Il y a 2j',
      title: 'Mise à jour EventPro 2.4',
      text: 'Découvrez le nouveau mode "Plan de salle interactif" pour vos prochaines réservations.',
      isNew: false,
      icon: 'information-circle-outline',
      iconBg: '#f0fdf4',
      iconColor: '#16a34a',
    }
  ];

  const filteredNotifications = notifications.filter(notif => {
    if (selectedFilter === 'Toutes') return true;
    if (selectedFilter === 'Événements') return notif.type === 'rappel' || notif.type === 'artiste';
    if (selectedFilter === 'Achats') return notif.type === 'achat';
    if (selectedFilter === 'Annonces') return notif.type === 'annonce';
    return true;
  });

  const renderNotificationItem = ({ item }) => {
    // Si c'est une annonce de type bannière
    if (item.type === 'annonce') {
      return (
        <TouchableOpacity style={styles.bannerCard} activeOpacity={0.95}>
          <ImageBackground
            source={{ uri: item.bgImage }}
            style={styles.bannerBg}
            imageStyle={{ borderRadius: 16 }}
          >
            <View style={styles.bannerOverlay}>
              <View style={styles.exclusivityBadge}>
                <Ionicons name="star" size={12} color="#ffffff" style={{ marginRight: 4 }} />
                <Text style={styles.exclusivityText}>{item.category}</Text>
              </View>
              <View style={styles.bannerFooter}>
                <Text style={styles.bannerTitle} numberOfLines={2}>{item.title}</Text>
                <TouchableOpacity style={styles.seeBtn}>
                  <Text style={styles.seeBtnText}>Voir</Text>
                </TouchableOpacity>
              </View>
            </View>
          </ImageBackground>
        </TouchableOpacity>
      );
    }

    // Sinon, notification standard
    return (
      <View style={[styles.card, item.isNew && styles.cardNew]}>
        <View style={[styles.iconContainer, { backgroundColor: item.iconBg }]}>
          <Ionicons name={item.icon} size={20} color={item.iconColor} />
        </View>
        <View style={styles.contentContainer}>
          <View style={styles.metaRow}>
            <Text style={[styles.categoryText, { color: item.iconColor }]}>{item.category}</Text>
            <Text style={styles.timeText}>{item.time}</Text>
          </View>
          <Text style={styles.titleText}>{item.title}</Text>
          <Text style={styles.descriptionText} numberOfLines={3}>{item.text}</Text>
          {item.isNew && (
            <View style={styles.newBadge}>
              <Text style={styles.newBadgeText}>Nouveau</Text>
            </View>
          )}
        </View>
      </View>
    );
  };

  return (
    <View style={styles.container}>
      {/* HEADER */}
      <View style={styles.header}>
        <TouchableOpacity style={styles.iconButton}>
          <Feather name="menu" size={24} color="#1e293b" />
        </TouchableOpacity>
        <Text style={styles.brandTitle}>EventPro</Text>
        <TouchableOpacity style={styles.iconButton}>
          <Feather name="settings" size={22} color="#1e293b" />
        </TouchableOpacity>
      </View>

      {/* FILTER TABS & SUB-HEADER */}
      <FlatList
        data={filteredNotifications}
        keyExtractor={(item) => item.id}
        renderItem={renderNotificationItem}
        contentContainerStyle={styles.listContent}
        ListHeaderComponent={
          <View style={styles.listHeader}>
            <View style={styles.titleRow}>
              <Text style={styles.screenTitle}>Notifications</Text>
              <TouchableOpacity>
                <Text style={styles.markAllReadText}>Tout marquer comme lu</Text>
              </TouchableOpacity>
            </View>

            {/* FILTERS */}
            <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.filtersScroll} contentContainerStyle={styles.filtersScrollContent}>
              {['Toutes', 'Événements', 'Achats', 'Annonces'].map((filter) => (
                <TouchableOpacity
                  key={filter}
                  style={[styles.filterPill, selectedFilter === filter && styles.filterPillActive]}
                  onPress={() => setSelectedFilter(filter)}
                >
                  <Text style={[styles.filterText, selectedFilter === filter && styles.filterTextActive]}>
                    {filter}
                  </Text>
                </TouchableOpacity>
              ))}
            </ScrollView>
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
    paddingTop: Platform.OS === 'ios' ? 55 : 20,
    paddingBottom: 12,
    backgroundColor: '#ffffff',
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  iconButton: {
    padding: 6,
  },
  brandTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#1e3a8a',
    letterSpacing: 0.5,
  },
  listHeader: {
    backgroundColor: '#f8fafc',
    paddingTop: 20,
    paddingBottom: 12,
  },
  titleRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    marginBottom: 16,
  },
  screenTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#1e3a8a',
  },
  markAllReadText: {
    fontSize: 13,
    color: '#ef4444',
    fontWeight: '700',
  },
  filtersScroll: {
    marginBottom: 6,
  },
  filtersScrollContent: {
    paddingHorizontal: 20,
  },
  filterPill: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
    backgroundColor: '#ffffff',
    marginRight: 8,
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  filterPillActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  filterText: {
    color: '#475569',
    fontSize: 14,
    fontWeight: '600',
  },
  filterTextActive: {
    color: '#ffffff',
  },
  listContent: {
    paddingBottom: 40,
  },
  card: {
    flexDirection: 'row',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    padding: 16,
    marginHorizontal: 20,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#f1f5f9',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 6,
    elevation: 2,
    position: 'relative',
  },
  cardNew: {
    borderLeftWidth: 4,
    borderLeftColor: '#1d4ed8',
  },
  iconContainer: {
    width: 40,
    height: 40,
    borderRadius: 20,
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  contentContainer: {
    flex: 1,
  },
  metaRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
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
  titleText: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#1e293b',
    lineHeight: 18,
    marginBottom: 4,
  },
  descriptionText: {
    fontSize: 13,
    color: '#64748b',
    lineHeight: 18,
  },
  newBadge: {
    alignSelf: 'flex-start',
    backgroundColor: '#fee2e2',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
    marginTop: 8,
  },
  newBadgeText: {
    color: '#ef4444',
    fontSize: 10,
    fontWeight: 'bold',
  },
  bannerCard: {
    height: 160,
    marginHorizontal: 20,
    marginBottom: 12,
    borderRadius: 16,
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  bannerBg: {
    width: '100%',
    height: '100%',
  },
  bannerOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.3)',
    padding: 16,
    justifyContent: 'space-between',
  },
  exclusivityBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    backgroundColor: '#d97706',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },
  exclusivityText: {
    color: '#ffffff',
    fontSize: 10,
    fontWeight: 'bold',
  },
  bannerFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  bannerTitle: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: 'bold',
    flex: 1,
    marginRight: 10,
    textShadowColor: 'rgba(0,0,0,0.6)',
    textShadowOffset: { width: 0, height: 1 },
    textShadowRadius: 3,
  },
  seeBtn: {
    backgroundColor: '#ef4444',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 8,
  },
  seeBtnText: {
    color: '#ffffff',
    fontWeight: 'bold',
    fontSize: 12,
  },
});
