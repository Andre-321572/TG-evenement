import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, TextInput, TouchableOpacity, ActivityIndicator, Image, ScrollView, Platform } from 'react-native';
import { Ionicons, Feather, MaterialCommunityIcons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function SearchScreen({ navigation }) {
  const [searchQuery, setSearchQuery] = useState('');
  const [showFilters, setShowFilters] = useState(true);
  const [selectedWhen, setSelectedWhen] = useState('Ce weekend');
  const [distance, setDistance] = useState(25);
  const [selectedPrice, setSelectedPrice] = useState('Paid');
  const [selectedCategory, setSelectedCategory] = useState('Musique');
  const [events, setEvents] = useState([]);
  const [isLoading, setIsLoading] = useState(false);

  // Événements de démonstration (pour correspondre exactement à la maquette 2 en fallback)
  const demoEvents = [
    {
      id: 'demo-1',
      titre: 'Neon Pulse Music Festival',
      categorie: 'FESTIVAL',
      date: 'Samedi, 21h00',
      lieu: 'Zénith, Paris',
      prix: 'À partir de 45€',
      photo_url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop',
    },
    {
      id: 'demo-2',
      titre: 'AI Future Summit 2024',
      categorie: 'TECH',
      date: 'Demain, 09h30',
      lieu: 'Station F, Paris',
      prix: 'Gratuit',
      photo_url: 'https://images.unsplash.com/photo-1507537297725-24a1c029d3ca?q=80&w=600&auto=format&fit=crop',
    },
    {
      id: 'demo-3',
      titre: 'Intimate Jazz Night',
      categorie: 'CONCERT',
      date: 'Ce weekend, 20h00',
      lieu: 'Duc des Lombards, Paris',
      prix: '25€',
      photo_url: 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?q=80&w=600&auto=format&fit=crop',
    },
    {
      id: 'demo-4',
      titre: 'Abstraction Moderne',
      categorie: 'EXPO',
      date: 'Dimanche, 11h00',
      lieu: 'Palais de Tokyo, Paris',
      prix: '12€',
      photo_url: 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?q=80&w=600&auto=format&fit=crop',
    }
  ];

  useEffect(() => {
    fetchEvents();
  }, [searchQuery, selectedWhen, selectedPrice, selectedCategory]);

  const fetchEvents = async () => {
    setIsLoading(true);
    try {
      const params = {
        search: searchQuery,
        categorie: selectedCategory === 'Tous' ? '' : selectedCategory,
      };
      const response = await apiClient.get('/events', { params });
      if (response.data.status === 'success' && response.data.data.data.length > 0) {
        // Formater les événements du backend pour correspondre à notre structure
        const formatted = response.data.data.data.map(item => ({
          id: item.id.toString(),
          titre: item.titre,
          categorie: item.categorie ? item.categorie.toUpperCase() : 'ÉVÉNEMENT',
          date: item.date, // ou formater
          lieu: item.lieu,
          prix: item.min_price > 0 ? `${item.min_price} FCFA` : 'Gratuit',
          photo_url: item.photo_url,
        }));
        setEvents(formatted);
      } else {
        // Utiliser les démos si la base de données est vide
        setEvents(demoEvents);
      }
    } catch (e) {
      console.error(e);
      setEvents(demoEvents);
    } finally {
      setIsLoading(false);
    }
  };

  const renderEventCard = ({ item }) => (
    <TouchableOpacity
      style={styles.eventCard}
      onPress={() => {
        if (!item.id.startsWith('demo-')) {
          navigation.navigate('EventDetail', { eventId: item.id });
        }
      }}
    >
      <View style={styles.imageWrapper}>
        <Image source={{ uri: item.photo_url }} style={styles.cardImage} />
        <View style={styles.cardCategoryBadge}>
          <Text style={styles.cardCategoryText}>{item.categorie}</Text>
        </View>
        <View style={styles.cardPriceBadge}>
          <Text style={styles.cardPriceText}>{item.prix}</Text>
        </View>
      </View>
      <View style={styles.cardBody}>
        <Text style={styles.cardTitle}>{item.titre}</Text>
        <Text style={styles.cardDetail}><Ionicons name="calendar-outline" size={13} color="#64748b" /> {item.date}</Text>
        <Text style={styles.cardDetail}><Ionicons name="location-outline" size={13} color="#64748b" /> {item.lieu}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {/* HEADER */}
      <View style={styles.header}>
        <TouchableOpacity style={styles.iconButton}>
          <Feather name="menu" size={24} color="#1e293b" />
        </TouchableOpacity>
        <Text style={styles.brandTitle}>EventPro</Text>
        <TouchableOpacity style={styles.avatarButton}>
          <Image
            source={{ uri: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=100&auto=format&fit=crop' }}
            style={styles.avatarImage}
          />
        </TouchableOpacity>
      </View>

      <FlatList
        data={events}
        keyExtractor={(item) => item.id}
        renderItem={renderEventCard}
        contentContainerStyle={styles.listContent}
        ListHeaderComponent={
          <View style={styles.searchAndFilters}>
            {/* SEARCH INPUT */}
            <View style={styles.searchSection}>
              <View style={styles.searchBarWrapper}>
                <Ionicons name="search-outline" size={20} color="#94a3b8" style={styles.searchIcon} />
                <TextInput
                  style={styles.searchInput}
                  placeholder="Rechercher des concerts, conférences..."
                  placeholderTextColor="#94a3b8"
                  value={searchQuery}
                  onChangeText={setSearchQuery}
                />
              </View>
            </View>

            {/* QUICK CATEGORIES FILTERS */}
            <View style={styles.categoriesSection}>
              <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.categoriesScroll}>
                <TouchableOpacity
                  style={[styles.filterToggleBtn, showFilters && styles.filterToggleBtnActive]}
                  onPress={() => setShowFilters(!showFilters)}
                >
                  <Ionicons name="options-outline" size={18} color={showFilters ? '#ffffff' : '#1e3a8a'} />
                  <Text style={[styles.filterToggleBtnText, showFilters && styles.filterToggleBtnTextActive]}>
                    Filtres
                  </Text>
                </TouchableOpacity>

                {['Musique', 'Tech', 'Festival', 'Théâtre', 'Exposition'].map((cat) => (
                  <TouchableOpacity
                    key={cat}
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

            {/* EXPANDABLE ADVANCED FILTERS PANEL */}
            {showFilters && (
              <View style={styles.filtersPanel}>
                {/* FILTER QUAND */}
                <View style={styles.filterGroup}>
                  <Text style={styles.filterLabel}>📅 Quand</Text>
                  <View style={styles.filterOptionsRow}>
                    {["Aujourd'hui", "Ce weekend", "Choisir..."].map((option) => (
                      <TouchableOpacity
                        key={option}
                        style={[styles.filterOptionBtn, selectedWhen === option && styles.filterOptionBtnActive]}
                        onPress={() => setSelectedWhen(option)}
                      >
                        <Text style={[styles.filterOptionBtnText, selectedWhen === option && styles.filterOptionBtnTextActive]}>
                          {option}
                        </Text>
                      </TouchableOpacity>
                    ))}
                  </View>
                </View>

                {/* FILTER LIEU / DISTANCE */}
                <View style={styles.filterGroup}>
                  <View style={styles.distanceLabelRow}>
                    <Text style={styles.filterLabel}>📍 Lieu</Text>
                    <Text style={styles.distanceValue}>Rayon : {distance} km</Text>
                  </View>
                  {/* Custom Slider View */}
                  <View style={styles.sliderContainer}>
                    <View style={styles.sliderTrack}>
                      <View style={[styles.sliderFill, { width: `${distance}%` }]} />
                      <TouchableOpacity
                        style={[styles.sliderThumb, { left: `${distance}%` }]}
                        activeOpacity={1}
                      />
                    </View>
                    <View style={styles.sliderLimitsRow}>
                      <TouchableOpacity onPress={() => setDistance(15)}><Text style={styles.limitText}>1km</Text></TouchableOpacity>
                      <TouchableOpacity onPress={() => setDistance(50)}><Text style={styles.limitText}>50km</Text></TouchableOpacity>
                      <TouchableOpacity onPress={() => setDistance(100)}><Text style={styles.limitText}>100km+</Text></TouchableOpacity>
                    </View>
                  </View>
                  <View style={styles.locationInputBox}>
                    <Ionicons name="navigate-outline" size={16} color="#1d4ed8" />
                    <Text style={styles.locationInputText}>Paris, France (Position Actuelle)</Text>
                  </View>
                </View>

                {/* FILTER PRIX */}
                <View style={styles.filterGroup}>
                  <Text style={styles.filterLabel}>💵 Prix</Text>
                  <div style={styles.filterOptionsRow}>
                    {["Gratuit", "Paid", "Premium"].map((option) => (
                      <TouchableOpacity
                        key={option}
                        style={[styles.filterOptionBtn, selectedPrice === option && styles.filterOptionBtnActive]}
                        onPress={() => setSelectedPrice(option)}
                      >
                        <Text style={[styles.filterOptionBtnText, selectedPrice === option && styles.filterOptionBtnTextActive]}>
                          {option === 'Paid' ? '€ Paid' : option === 'Premium' ? '€€ Premium' : option}
                        </Text>
                      </TouchableOpacity>
                    ))}
                  </div>
                </View>
              </View>
            )}

            {/* RESULTS STATISTICS BAR */}
            <View style={styles.resultsHeaderRow}>
              <Text style={styles.resultsCountText}>Résultats ({events.length})</Text>
              <TouchableOpacity style={styles.sortBtn}>
                <Text style={styles.sortBtnText}>Trier par : Pertinence</Text>
                <Ionicons name="chevron-down" size={14} color="#1d4ed8" />
              </TouchableOpacity>
            </View>
          </View>
        }
        ListEmptyComponent={
          isLoading ? (
            <ActivityIndicator size="large" color="#1d4ed8" style={{ marginTop: 30 }} />
          ) : (
            <View style={styles.emptyContainer}>
              <Text style={styles.emptyText}>Aucun résultat ne correspond à vos filtres.</Text>
            </View>
          )
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
  listContent: {
    paddingBottom: 30,
  },
  searchAndFilters: {
    backgroundColor: '#f8fafc',
  },
  searchSection: {
    backgroundColor: '#ffffff',
    paddingHorizontal: 20,
    paddingVertical: 12,
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
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  categoriesScroll: {
    paddingHorizontal: 20,
    alignItems: 'center',
  },
  filterToggleBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
    backgroundColor: '#ffffff',
    marginRight: 8,
    borderWidth: 1,
    borderColor: '#1e3a8a',
    gap: 6,
  },
  filterToggleBtnActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  filterToggleBtnText: {
    color: '#1e3a8a',
    fontSize: 14,
    fontWeight: 'bold',
  },
  filterToggleBtnTextActive: {
    color: '#ffffff',
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
    backgroundColor: '#e0e7ff',
    borderColor: '#1d4ed8',
  },
  categoryText: {
    color: '#475569',
    fontSize: 14,
    fontWeight: '600',
  },
  categoryTextActive: {
    color: '#1d4ed8',
  },
  filtersPanel: {
    backgroundColor: '#ffffff',
    marginHorizontal: 20,
    marginTop: 14,
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.04,
    shadowRadius: 10,
    elevation: 3,
  },
  filterGroup: {
    marginBottom: 16,
  },
  filterLabel: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#1e293b',
    marginBottom: 10,
  },
  filterOptionsRow: {
    flexDirection: 'row',
    gap: 10,
  },
  filterOptionBtn: {
    flex: 1,
    paddingVertical: 10,
    borderRadius: 10,
    backgroundColor: '#f8fafc',
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  filterOptionBtnActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  filterOptionBtnText: {
    fontSize: 13,
    fontWeight: '600',
    color: '#475569',
  },
  filterOptionBtnTextActive: {
    color: '#ffffff',
  },
  distanceLabelRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  distanceValue: {
    fontSize: 13,
    fontWeight: 'bold',
    color: '#1d4ed8',
  },
  sliderContainer: {
    marginVertical: 8,
  },
  sliderTrack: {
    height: 6,
    borderRadius: 3,
    backgroundColor: '#e2e8f0',
    position: 'relative',
  },
  sliderFill: {
    height: '100%',
    backgroundColor: '#1d4ed8',
    borderRadius: 3,
  },
  sliderThumb: {
    width: 18,
    height: 18,
    borderRadius: 9,
    backgroundColor: '#1d4ed8',
    position: 'absolute',
    top: -6,
    marginLeft: -9,
    borderWidth: 2,
    borderColor: '#ffffff',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.2,
    shadowRadius: 1.5,
    elevation: 2,
  },
  sliderLimitsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 6,
  },
  limitText: {
    fontSize: 11,
    color: '#94a3b8',
  },
  locationInputBox: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#eff6ff',
    borderRadius: 10,
    paddingHorizontal: 12,
    paddingVertical: 10,
    marginTop: 10,
    gap: 8,
  },
  locationInputText: {
    fontSize: 13,
    color: '#1e3a8a',
    fontWeight: '500',
  },
  resultsHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingTop: 16,
    paddingBottom: 8,
  },
  resultsCountText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#0f172a',
  },
  sortBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  sortBtnText: {
    fontSize: 13,
    color: '#1d4ed8',
    fontWeight: '600',
  },
  eventCard: {
    backgroundColor: '#ffffff',
    borderRadius: 16,
    marginHorizontal: 20,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#f1f5f9',
    overflow: 'hidden',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.04,
    shadowRadius: 6,
    elevation: 2,
  },
  imageWrapper: {
    position: 'relative',
    height: 160,
    width: '100%',
  },
  cardImage: {
    width: '100%',
    height: '100%',
    resizeMode: 'cover',
  },
  cardCategoryBadge: {
    position: 'absolute',
    top: 12,
    left: 12,
    backgroundColor: '#1d4ed8',
    borderRadius: 6,
    paddingHorizontal: 8,
    paddingVertical: 4,
  },
  cardCategoryText: {
    color: '#ffffff',
    fontSize: 10,
    fontWeight: 'bold',
  },
  cardPriceBadge: {
    position: 'absolute',
    bottom: 12,
    right: 12,
    backgroundColor: 'rgba(255, 255, 255, 0.95)',
    borderRadius: 8,
    paddingHorizontal: 10,
    paddingVertical: 5,
  },
  cardPriceText: {
    color: '#0f172a',
    fontSize: 12,
    fontWeight: 'bold',
  },
  cardBody: {
    padding: 14,
  },
  cardTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1e293b',
    marginBottom: 6,
  },
  cardDetail: {
    fontSize: 13,
    color: '#64748b',
    marginTop: 4,
  },
  emptyContainer: {
    alignItems: 'center',
    paddingVertical: 40,
  },
  emptyText: {
    color: '#94a3b8',
    fontSize: 14,
  },
});
