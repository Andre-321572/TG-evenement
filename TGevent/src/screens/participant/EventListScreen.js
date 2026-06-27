import React, { useState, useEffect, useContext } from 'react';
import { StyleSheet, Text, View, FlatList, Image, TextInput, TouchableOpacity, ActivityIndicator, RefreshControl, Platform, ScrollView, ImageBackground } from 'react-native';
import { Ionicons, Feather } from '@expo/vector-icons';
import apiClient from '../../api/client';
import { AuthContext } from '../../context/AuthContext';

export default function EventListScreen({ navigation }) {
  const { user } = useContext(AuthContext);
  const [events, setEvents] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState('');
  const [search, setSearch] = useState('');
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchEvents(true);
    fetchCategories();
  }, [selectedCategory]);

  const fetchCategories = async () => {
    try {
      const response = await apiClient.get('/categories');
      if (response.data.status === 'success') {
        setCategories(response.data.categories);
      }
    } catch (e) {
      console.error('Erreur categories', e);
    }
  };

  const fetchEvents = async (shouldReset = false) => {
    setIsLoading(true);
    try {
      const params = {
        search: search,
        categorie: selectedCategory,
      };
      const response = await apiClient.get('/events', { params });
      if (response.data.status === 'success') {
        const fetchedData = response.data.data.data;
        setEvents(fetchedData);
      }
    } catch (e) {
      console.error('Erreur lors du chargement des événements', e);
    } finally {
      setIsLoading(false);
      setRefreshing(false);
    }
  };

  const handleRefresh = () => {
    setRefreshing(true);
    fetchEvents(true);
  };

  const handleSearchSubmit = () => {
    fetchEvents(true);
  };

  // Helper pour formater les dates sous la forme "12 MAI" pour le badge
  const formatDateBadge = (dateString) => {
    if (!dateString) return 'TBA';
    try {
      const months = ['JAN', 'FÉV', 'MAR', 'AVR', 'MAI', 'JUI', 'JUL', 'AOÛ', 'SEP', 'OCT', 'NOV', 'DÉC'];
      const date = new Date(dateString);
      const day = date.getDate();
      const month = months[date.getMonth()];
      return `${day}\n${month}`;
    } catch (e) {
      return 'TBA';
    }
  };

  // Helper pour formater la date complète en français
  const formatEventDate = (dateString) => {
    if (!dateString) return '';
    try {
      const date = new Date(dateString);
      const days = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
      const months = ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'];
      return `${days[date.getDay()]} ${date.getDate()} ${months[date.getMonth()]}`;
    } catch (e) {
      return dateString;
    }
  };

  // Rendu de la section supérieure (Header, Recherche, Catégories, À l'affiche)
  const renderHeader = () => {
    // Événements "À l'affiche" (on prend les 3 premiers événements comme mis en avant)
    const featuredEvents = events.slice(0, 3);

    return (
      <View style={styles.headerContainer}>
        {/* TOP BAR */}
        <View style={styles.topBar}>
          <TouchableOpacity style={styles.iconButton}>
            <Feather name="menu" size={24} color="#1e293b" />
          </TouchableOpacity>
          <Text style={styles.brandTitle}>EventPro</Text>
          <TouchableOpacity style={styles.avatarButton} onPress={() => navigation.navigate('Profil')}>
            {user?.img_profil ? (
              <Image source={{ uri: user.img_profil }} style={styles.avatarImage} />
            ) : (
              <View style={styles.avatarFallback}>
                <Text style={styles.avatarText}>
                  {user?.prenom?.charAt(0).toUpperCase() || 'U'}
                </Text>
              </View>
            )}
          </TouchableOpacity>
        </View>

        {/* SEARCH BAR */}
        <View style={styles.searchSection}>
          <View style={styles.searchBarWrapper}>
            <Ionicons name="search-outline" size={20} color="#94a3b8" style={styles.searchIcon} />
            <TextInput
              style={styles.searchInput}
              placeholder="Rechercher des événements, artistes..."
              placeholderTextColor="#94a3b8"
              value={search}
              onChangeText={setSearch}
              onSubmitEditing={handleSearchSubmit}
            />
            {search.length > 0 && (
              <TouchableOpacity onPress={() => { setSearch(''); fetchEvents(true); }}>
                <Ionicons name="close-circle" size={18} color="#94a3b8" />
              </TouchableOpacity>
            )}
          </View>
        </View>

        {/* CATEGORIES PILLS */}
        <View style={styles.categoriesSection}>
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.categoriesScroll}>
            <TouchableOpacity
              style={[styles.categoryPill, selectedCategory === '' && styles.categoryPillActive]}
              onPress={() => setSelectedCategory('')}
            >
              <Text style={[styles.categoryText, selectedCategory === '' && styles.categoryTextActive]}>
                Tout
              </Text>
            </TouchableOpacity>
            {categories.map((cat, index) => (
              <TouchableOpacity
                key={index}
                style={[styles.categoryPill, selectedCategory === cat && styles.categoryPillActive]}
                onPress={() => setSelectedCategory(cat)}
              >
                <Text style={[styles.categoryText, selectedCategory === cat && styles.categoryTextActive]}>
                  {cat}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>
        </View>

        {/* SECTION À L'AFFICHE */}
        {featuredEvents.length > 0 && (
          <View style={styles.featuredSection}>
            <View style={styles.sectionHeader}>
              <Text style={styles.sectionTitle}>À l'affiche</Text>
              <TouchableOpacity>
                <Text style={styles.seeAllLink}>Voir tout</Text>
              </TouchableOpacity>
            </View>
            <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.featuredScroll}>
              {featuredEvents.map((item) => (
                <TouchableOpacity
                  key={item.id}
                  style={styles.featuredCard}
                  onPress={() => navigation.navigate('EventDetail', { eventId: item.id })}
                >
                  <ImageBackground
                    source={{ uri: item.photo_url }}
                    style={styles.featuredImage}
                    imageStyle={{ borderRadius: 16 }}
                  >
                    <View style={styles.featuredOverlay}>
                      <View style={styles.popularBadge}>
                        <Text style={styles.popularBadgeText}>POPULAIRE</Text>
                      </View>
                      <View style={styles.featuredInfo}>
                        <Text style={styles.featuredTitle} numberOfLines={2}>{item.titre}</Text>
                        <Text style={styles.featuredLocation}>📍 {item.lieu}</Text>
                      </View>
                    </View>
                  </ImageBackground>
                </TouchableOpacity>
              ))}
            </ScrollView>
          </View>
        )}

        {/* SECTION RECOMMANDÉ POUR VOUS HEADER */}
        <View style={styles.recommendedHeader}>
          <Text style={styles.sectionTitle}>Recommandé pour vous</Text>
          <Text style={styles.sectionSubtitle}>Basé sur vos préférences</Text>
        </View>
      </View>
    );
  };

  // Rendu de chaque élément recommandé (Carte verticale stylisée)
  const renderEventItem = ({ item }) => (
    <TouchableOpacity
      style={styles.eventCard}
      onPress={() => navigation.navigate('EventDetail', { eventId: item.id })}
    >
      <View style={styles.eventImageContainer}>
        <Image source={{ uri: item.photo_url }} style={styles.eventImage} />
        <View style={styles.dateBadge}>
          <Text style={styles.dateBadgeText}>{formatDateBadge(item.date)}</Text>
        </View>
      </View>
      <View style={styles.eventDetails}>
        <View>
          <Text style={styles.eventTitle} numberOfLines={2}>{item.titre}</Text>
          <Text style={styles.eventLocation}>📍 {item.lieu}</Text>
          <Text style={styles.eventDateText}>📅 {formatEventDate(item.date)}</Text>
        </View>
        <View style={styles.eventFooter}>
          <Text style={styles.eventPrice}>{item.min_price > 0 ? `${item.min_price} FCFA` : 'Gratuit'}</Text>
          <TouchableOpacity
            style={styles.reserveButton}
            onPress={() => navigation.navigate('EventDetail', { eventId: item.id })}
          >
            <Text style={styles.reserveButtonText}>Réserver</Text>
          </TouchableOpacity>
        </View>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <FlatList
        data={events}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderEventItem}
        ListHeaderComponent={renderHeader}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#1d4ed8" />
        }
        ListEmptyComponent={
          !isLoading && (
            <View style={styles.emptyContainer}>
              <Ionicons name="calendar-outline" size={48} color="#94a3b8" />
              <Text style={styles.emptyText}>Aucun événement trouvé.</Text>
            </View>
          )
        }
        ListFooterComponent={
          isLoading && <ActivityIndicator size="small" color="#1d4ed8" style={{ marginVertical: 20 }} />
        }
        contentContainerStyle={styles.listContent}
      />

      {/* FLOATING ACTION BUTTON */}
      {(user?.role === 'admin' || user?.role === 'organisateur') && (
        <TouchableOpacity
          style={styles.fab}
          onPress={() => {
            if (navigation.navigate) {
              navigation.navigate('Créer Événement');
            }
          }}
        >
          <Ionicons name="add" size={28} color="#fff" />
        </TouchableOpacity>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  listContent: {
    paddingBottom: 30,
  },
  headerContainer: {
    backgroundColor: '#f8fafc',
  },
  topBar: {
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
  avatarButton: {
    width: 38,
    height: 38,
    borderRadius: 19,
    overflow: 'hidden',
  },
  avatarImage: {
    width: '100%',
    height: '100%',
    resizeMode: 'cover',
  },
  avatarFallback: {
    width: '100%',
    height: '100%',
    backgroundColor: '#e0e7ff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  avatarText: {
    color: '#1d4ed8',
    fontWeight: 'bold',
    fontSize: 16,
  },
  searchSection: {
    backgroundColor: '#ffffff',
    paddingHorizontal: 20,
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  searchBarWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f1f5f9',
    borderRadius: 12,
    paddingHorizontal: 12,
    height: 46,
  },
  searchIcon: {
    marginRight: 8,
  },
  searchInput: {
    flex: 1,
    color: '#1e293b',
    fontSize: 14,
    height: '100%',
  },
  categoriesSection: {
    backgroundColor: '#f8fafc',
    paddingVertical: 14,
  },
  categoriesScroll: {
    paddingHorizontal: 20,
  },
  categoryPill: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
    backgroundColor: '#ffffff',
    marginRight: 8,
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  categoryPillActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  categoryText: {
    color: '#475569',
    fontSize: 14,
    fontWeight: '600',
  },
  categoryTextActive: {
    color: '#ffffff',
  },
  featuredSection: {
    paddingHorizontal: 20,
    marginBottom: 20,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#0f172a',
  },
  sectionSubtitle: {
    fontSize: 12,
    color: '#64748b',
    marginTop: 2,
  },
  seeAllLink: {
    fontSize: 14,
    color: '#ef4444',
    fontWeight: '700',
  },
  featuredScroll: {
    paddingRight: 20,
  },
  featuredCard: {
    width: 280,
    height: 160,
    marginRight: 14,
    borderRadius: 16,
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  featuredImage: {
    width: '100%',
    height: '100%',
  },
  featuredOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.35)',
    justifyContent: 'space-between',
    padding: 14,
  },
  popularBadge: {
    alignSelf: 'flex-start',
    backgroundColor: '#ef4444',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },
  popularBadgeText: {
    color: '#ffffff',
    fontSize: 10,
    fontWeight: 'bold',
  },
  featuredInfo: {
    marginTop: 'auto',
  },
  featuredTitle: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: 'bold',
    marginBottom: 4,
    textShadowColor: 'rgba(0,0,0,0.7)',
    textShadowOffset: { width: 0, height: 1 },
    textShadowRadius: 3,
  },
  featuredLocation: {
    color: '#ffffff',
    fontSize: 12,
    textShadowColor: 'rgba(0,0,0,0.7)',
    textShadowOffset: { width: 0, height: 1 },
    textShadowRadius: 3,
  },
  recommendedHeader: {
    paddingHorizontal: 20,
    marginBottom: 14,
  },
  eventCard: {
    flexDirection: 'row',
    backgroundColor: '#ffffff',
    borderRadius: 16,
    padding: 10,
    marginHorizontal: 20,
    marginBottom: 14,
    borderWidth: 1,
    borderColor: '#f1f5f9',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.04,
    shadowRadius: 6,
    elevation: 2,
  },
  eventImageContainer: {
    position: 'relative',
  },
  eventImage: {
    width: 90,
    height: 90,
    borderRadius: 12,
  },
  dateBadge: {
    position: 'absolute',
    top: 6,
    right: 6,
    backgroundColor: 'rgba(255, 255, 255, 0.9)',
    borderRadius: 8,
    paddingHorizontal: 6,
    paddingVertical: 4,
    alignItems: 'center',
    justifyContent: 'center',
    minWidth: 38,
  },
  dateBadgeText: {
    fontSize: 9,
    fontWeight: 'bold',
    color: '#1e3a8a',
    textAlign: 'center',
    lineHeight: 11,
  },
  eventDetails: {
    flex: 1,
    marginLeft: 12,
    justifyContent: 'space-between',
  },
  eventTitle: {
    fontSize: 15,
    fontWeight: 'bold',
    color: '#1e293b',
    lineHeight: 18,
  },
  eventLocation: {
    fontSize: 12,
    color: '#64748b',
    marginTop: 2,
  },
  eventDateText: {
    fontSize: 11,
    color: '#64748b',
    marginTop: 2,
  },
  eventFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 6,
  },
  eventPrice: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#ef4444',
  },
  reserveButton: {
    backgroundColor: '#fee2e2',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 8,
  },
  reserveButtonText: {
    color: '#ef4444',
    fontSize: 12,
    fontWeight: 'bold',
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 40,
  },
  emptyText: {
    color: '#94a3b8',
    marginTop: 10,
    fontSize: 15,
  },
  fab: {
    position: 'absolute',
    bottom: 20,
    right: 20,
    width: 54,
    height: 54,
    borderRadius: 27,
    backgroundColor: '#1e3a8a',
    alignItems: 'center',
    justifyContent: 'center',
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 3.84,
  },
});
