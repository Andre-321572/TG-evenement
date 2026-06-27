import React, { useState, useEffect, useContext } from 'react';
import { StyleSheet, Text, View, FlatList, Image, TextInput, TouchableOpacity, ActivityIndicator, RefreshControl, ScrollView, Dimensions } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';
import { AuthContext } from '../../context/AuthContext';

const { width } = Dimensions.get('window');

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
      console.error('Erreur chargement événements', e);
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

  // Les événements populaires pour la section "À l'affiche" (ex: les 3 premiers)
  const featuredEvents = events.slice(0, 3);
  // Les autres événements pour la section "Recommandé pour vous"
  const recommendedEvents = events.slice(3);

  const renderFeaturedItem = ({ item }) => (
    <TouchableOpacity
      style={styles.featuredCard}
      onPress={() => navigation.navigate('EventDetail', { eventId: item.id })}
    >
      <Image source={{ uri: item.photo_url }} style={styles.featuredImage} />
      <View style={styles.gradientOverlay}>
        <View style={styles.popularBadge}>
          <Text style={styles.popularText}>POPULAIRE</Text>
        </View>
        <Text style={styles.featuredTitle}>{item.titre}</Text>
        <Text style={styles.featuredLocation}>📍 {item.lieu}</Text>
      </View>
    </TouchableOpacity>
  );

  const renderRecommendedItem = ({ item }) => {
    // Formater la date en jj MMM (ex: "12 MAI")
    let formattedDate = "Aujourd'hui";
    if (item.date) {
      const dateParts = item.date.split('-');
      if (dateParts.length === 3) {
        const monthNames = ["JAN", "FEV", "MAR", "AVR", "MAI", "JUN", "JUL", "AOÛ", "SEP", "OCT", "NOV", "DEC"];
        const day = parseInt(dateParts[2], 10);
        const monthIndex = parseInt(dateParts[1], 10) - 1;
        formattedDate = `${day} ${monthNames[monthIndex]}`;
      }
    }

    return (
      <TouchableOpacity
        style={styles.recommendedCard}
        onPress={() => navigation.navigate('EventDetail', { eventId: item.id })}
      >
        <View style={styles.recommendedImageContainer}>
          <Image source={{ uri: item.photo_url }} style={styles.recommendedImage} />
          <View style={styles.dateBadge}>
            <Text style={styles.dateBadgeText}>{formattedDate}</Text>
          </View>
        </View>
        
        <View style={styles.recommendedInfo}>
          <Text style={styles.recommendedTitle} numberOfLines={1}>{item.titre}</Text>
          <Text style={styles.recommendedLocation} numberOfLines={1}>📍 {item.lieu}</Text>
          <View style={styles.recommendedBottom}>
            <Text style={styles.recommendedPrice}>
              {item.min_price === 0 ? 'Gratuit' : `${item.min_price} FCFA`}
            </Text>
            <View style={styles.reserveButton}>
              <Text style={styles.reserveButtonText}>Réserver</Text>
            </View>
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  return (
    <View style={styles.container}>
      {/* App Custom Premium Header */}
      <View style={styles.appHeader}>
        <TouchableOpacity style={styles.menuButton}>
          <Ionicons name="menu-outline" size={26} color="#1e3a8a" />
        </TouchableOpacity>
        <Text style={styles.logoText}>TGevent</Text>
        <TouchableOpacity 
          style={styles.profileAvatar}
          onPress={() => navigation.navigate('Profil')}
        >
          {user ? (
            user.img_profil ? (
              <Image source={{ uri: user.img_profil }} style={styles.avatarImage} />
            ) : (
              <View style={styles.avatarFallback}>
                <Text style={styles.avatarFallbackText}>
                  {user.prenom?.charAt(0).toUpperCase()}
                  {user.nom?.charAt(0).toUpperCase()}
                </Text>
              </View>
            )
          ) : (
            <View style={styles.avatarFallbackGuest}>
              <Ionicons name="person" size={18} color="#fff" />
            </View>
          )}
        </TouchableOpacity>
      </View>

      {/* Main Scroll Content */}
      <ScrollView
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={handleRefresh} tintColor="#2563eb" />
        }
      >
        {/* Search Bar */}
        <View style={styles.searchBar}>
          <View style={styles.searchInputContainer}>
            <Ionicons name="search" size={20} color="#94a3b8" style={styles.searchIcon} />
            <TextInput
              style={styles.searchInput}
              placeholder="Rechercher des événements, artistes..."
              placeholderTextColor="#94a3b8"
              value={search}
              onChangeText={setSearch}
              onSubmitEditing={handleSearchSubmit}
            />
          </View>
        </View>

        {/* Categories Horizontal List */}
        <View style={styles.categoriesContainer}>
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={{ paddingHorizontal: 20 }}>
            {['', ...categories].map((item, index) => {
              const isSelected = selectedCategory === item;
              return (
                <TouchableOpacity
                  key={index}
                  style={[
                    styles.categoryButton,
                    isSelected && styles.categoryButtonActive,
                  ]}
                  onPress={() => setSelectedCategory(item)}
                >
                  <Text
                    style={[
                      styles.categoryButtonText,
                      isSelected && styles.categoryButtonTextActive,
                    ]}
                  >
                    {item === '' ? 'Tout' : item}
                  </Text>
                </TouchableOpacity>
              );
            })}
          </ScrollView>
        </View>

        {isLoading && !refreshing ? (
          <View style={styles.loaderContainer}>
            <ActivityIndicator size="large" color="#2563eb" />
          </View>
        ) : (
          <>
            {/* Section: À l'affiche */}
            {featuredEvents.length > 0 && (
              <View style={styles.sectionContainer}>
                <View style={styles.sectionHeader}>
                  <Text style={styles.sectionTitle}>À l'affiche</Text>
                  <TouchableOpacity onPress={() => navigation.navigate('Recherche')}>
                    <Text style={styles.seeAllText}>Voir tout</Text>
                  </TouchableOpacity>
                </View>
                <FlatList
                  horizontal
                  showsHorizontalScrollIndicator={false}
                  data={featuredEvents}
                  keyExtractor={(item) => item.id.toString()}
                  renderItem={renderFeaturedItem}
                  contentContainerStyle={{ paddingHorizontal: 20 }}
                />
              </View>
            )}

            {/* Section: Recommandé pour vous */}
            <View style={styles.sectionContainer}>
              <Text style={styles.sectionTitleRecommended}>Recommandé pour vous</Text>
              <Text style={styles.sectionSubtitle}>Basé sur vos préférences</Text>
              
              {recommendedEvents.length === 0 && featuredEvents.length === 0 ? (
                <View style={styles.emptyContainer}>
                  <Text style={styles.emptyText}>Aucun événement trouvé.</Text>
                </View>
              ) : (
                <FlatList
                  scrollEnabled={false}
                  data={recommendedEvents.length > 0 ? recommendedEvents : featuredEvents}
                  keyExtractor={(item) => item.id.toString()}
                  renderItem={renderRecommendedItem}
                  contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 20 }}
                />
              )}
            </View>
          </>
        )}
      </ScrollView>

      {/* Floating Action Button (FAB) + to simulate the mockup */}
      <TouchableOpacity 
        style={styles.fab}
        onPress={() => {
          if (user?.role === 'admin' || user?.role === 'organisateur') {
            navigation.navigate('OrganizerHome', { screen: 'Créer Événement' });
          } else {
            Alert.alert('Créer un événement', 'Devenez organisateur sur notre site web pour publier vos événements !');
          }
        }}
      >
        <Ionicons name="add" size={28} color="#fff" />
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  appHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingTop: 16,
    paddingBottom: 10,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  menuButton: {
    padding: 4,
  },
  logoText: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#1e3a8a',
    letterSpacing: 0.5,
  },
  profileAvatar: {
    width: 36,
    height: 36,
    borderRadius: 18,
    overflow: 'hidden',
  },
  avatarImage: {
    width: '100%',
    height: '100%',
  },
  avatarFallback: {
    width: '100%',
    height: '100%',
    backgroundColor: '#2563eb',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarFallbackText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: 'bold',
  },
  avatarFallbackGuest: {
    width: '100%',
    height: '100%',
    backgroundColor: '#94a3b8',
    justifyContent: 'center',
    alignItems: 'center',
  },
  searchBar: {
    paddingHorizontal: 20,
    paddingVertical: 16,
    backgroundColor: '#fff',
  },
  searchInputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f1f5f9',
    borderRadius: 12,
    paddingHorizontal: 16,
    height: 48,
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  searchIcon: {
    marginRight: 8,
  },
  searchInput: {
    flex: 1,
    color: '#0f172a',
    fontSize: 15,
  },
  categoriesContainer: {
    backgroundColor: '#fff',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  categoryButton: {
    paddingHorizontal: 18,
    paddingVertical: 8,
    borderRadius: 99,
    backgroundColor: '#eff6ff',
    marginRight: 10,
    borderWidth: 1,
    borderColor: '#dbeafe',
  },
  categoryButtonActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  categoryButtonText: {
    color: '#1e3a8a',
    fontSize: 13,
    fontWeight: '600',
  },
  categoryButtonTextActive: {
    color: '#fff',
  },
  loaderContainer: {
    height: 300,
    justifyContent: 'center',
    alignItems: 'center',
  },
  sectionContainer: {
    marginTop: 24,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    marginBottom: 12,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#1e3a8a',
  },
  seeAllText: {
    fontSize: 13,
    color: '#2563eb',
    fontWeight: '600',
  },
  featuredCard: {
    width: width * 0.75,
    height: 180,
    borderRadius: 16,
    overflow: 'hidden',
    marginRight: 16,
    position: 'relative',
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  featuredImage: {
    width: '100%',
    height: '100%',
  },
  gradientOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(15, 23, 42, 0.35)',
    padding: 16,
    justifyContent: 'flex-end',
  },
  popularBadge: {
    position: 'absolute',
    top: 12,
    left: 12,
    backgroundColor: '#f43f5e',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },
  popularText: {
    color: '#fff',
    fontSize: 10,
    fontWeight: 'bold',
  },
  featuredTitle: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
    marginBottom: 4,
  },
  featuredLocation: {
    color: '#cbd5e1',
    fontSize: 12,
  },
  sectionTitleRecommended: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#1e3a8a',
    paddingHorizontal: 20,
  },
  sectionSubtitle: {
    fontSize: 13,
    color: '#64748b',
    paddingHorizontal: 20,
    marginBottom: 16,
  },
  recommendedCard: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 4,
    elevation: 1,
  },
  recommendedImageContainer: {
    position: 'relative',
    width: 100,
    height: 100,
    borderRadius: 8,
    overflow: 'hidden',
  },
  recommendedImage: {
    width: '100%',
    height: '100%',
  },
  dateBadge: {
    position: 'absolute',
    top: 6,
    left: 6,
    backgroundColor: 'rgba(15, 23, 42, 0.75)',
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 4,
  },
  dateBadgeText: {
    color: '#fff',
    fontSize: 8,
    fontWeight: 'bold',
  },
  recommendedInfo: {
    flex: 1,
    marginLeft: 12,
    justifyContent: 'space-between',
  },
  recommendedTitle: {
    fontSize: 15,
    fontWeight: 'bold',
    color: '#1e3a8a',
  },
  recommendedLocation: {
    fontSize: 12,
    color: '#64748b',
    marginTop: 2,
  },
  recommendedBottom: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 8,
  },
  recommendedPrice: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#0f172a',
  },
  reserveButton: {
    backgroundColor: '#ffe4e6',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 6,
  },
  reserveButtonText: {
    color: '#e11d48',
    fontWeight: 'bold',
    fontSize: 12,
  },
  emptyContainer: {
    padding: 32,
    alignItems: 'center',
  },
  emptyText: {
    color: '#64748b',
    fontSize: 14,
  },
  fab: {
    position: 'absolute',
    bottom: 24,
    right: 24,
    width: 56,
    height: 56,
    borderRadius: 28,
    backgroundColor: '#1e3a8a',
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#1e3a8a',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 6,
    elevation: 5,
  },
});
