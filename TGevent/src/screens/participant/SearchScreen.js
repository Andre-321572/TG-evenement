import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, FlatList, Image, ActivityIndicator, ScrollView } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function SearchScreen({ navigation }) {
  const [search, setSearch] = useState('');
  const [showFilters, setShowFilters] = useState(true);
  const [selectedWhen, setSelectedWhen] = useState('Ce weekend'); // 'Aujourd'hui', 'Ce weekend', 'Choisir...'
  const [radius, setRadius] = useState(25);
  const [priceFilter, setPriceFilter] = useState('Paid'); // 'Gratuit', 'Paid', 'Premium'
  
  const [events, setEvents] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [resultsCount, setResultsCount] = useState(0);

  useEffect(() => {
    fetchSearchResults();
  }, [search, selectedWhen, priceFilter]);

  const fetchSearchResults = async () => {
    setIsLoading(true);
    try {
      // Préparer les paramètres basés sur les filtres
      const params = {
        search: search,
      };

      // Simuler le filtre de date pour l'API
      if (selectedWhen === "Aujourd'hui") {
        const today = new Date().toISOString().split('T')[0];
        params.date_start = today;
        params.date_end = today;
      } else if (selectedWhen === 'Ce weekend') {
        // Simuler le weekend
        const today = new Date();
        const nextFriday = new Date(today.setDate(today.getDate() + (5 - today.getDay())));
        const nextSunday = new Date(today.setDate(today.getDate() + 2));
        params.date_start = nextFriday.toISOString().split('T')[0];
        params.date_end = nextSunday.toISOString().split('T')[0];
      }

      const response = await apiClient.get('/events', { params });
      if (response.data.status === 'success') {
        let fetchedEvents = response.data.data.data;

        // Filtrage local supplémentaire pour correspondre aux filtres Prix du mockup
        if (priceFilter === 'Gratuit') {
          fetchedEvents = fetchedEvents.filter(e => e.min_price === 0);
        } else if (priceFilter === 'Paid') {
          fetchedEvents = fetchedEvents.filter(e => e.min_price > 0 && e.min_price < 20000);
        } else if (priceFilter === 'Premium') {
          fetchedEvents = fetchedEvents.filter(e => e.min_price >= 20000);
        }

        setEvents(fetchedEvents);
        setResultsCount(fetchedEvents.length);
      }
    } catch (e) {
      console.error('Erreur recherche', e);
    } finally {
      setIsLoading(false);
    }
  };

  const renderEventItem = ({ item }) => (
    <TouchableOpacity
      style={styles.card}
      onPress={() => navigation.navigate('EventDetail', { eventId: item.id })}
    >
      <View style={styles.imageContainer}>
        <Image source={{ uri: item.photo_url }} style={styles.image} />
        <View style={styles.categoryBadge}>
          <Text style={styles.categoryBadgeText}>{item.categorie?.toUpperCase()}</Text>
        </View>
        <View style={styles.priceBadge}>
          <Text style={styles.priceBadgeText}>
            {item.min_price === 0 ? 'Gratuit' : `À partir de ${item.min_price} FCFA`}
          </Text>
        </View>
      </View>
      <View style={styles.cardBody}>
        <Text style={styles.title}>{item.titre}</Text>
        <Text style={styles.dateTime}>📅 {item.formatted_date || item.date} - {item.start_heure}</Text>
        <Text style={styles.location}>📍 {item.lieu}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {/* Search Header */}
      <View style={styles.header}>
        <View style={styles.searchBarContainer}>
          <Ionicons name="search" size={20} color="#94a3b8" style={styles.searchIcon} />
          <TextInput
            style={styles.searchInput}
            placeholder="Rechercher des concerts, conférences..."
            placeholderTextColor="#94a3b8"
            value={search}
            onChangeText={setSearch}
          />
        </View>
      </View>

      {/* Filter Toggle Button and category quick view */}
      <View style={styles.filtersBar}>
        <TouchableOpacity 
          style={[styles.filterToggle, showFilters && styles.filterToggleActive]} 
          onPress={() => setShowFilters(!showFilters)}
        >
          <Ionicons name="options-outline" size={18} color={showFilters ? '#fff' : '#1e3a8a'} />
          <Text style={[styles.filterToggleText, showFilters && styles.filterToggleTextActive]}>Filtres</Text>
        </TouchableOpacity>

        <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.quickTags}>
          {['Musique', 'Tech', 'Festivals', 'Sport'].map((tag) => (
            <TouchableOpacity key={tag} style={styles.tagButton}>
              <Text style={styles.tagButtonText}>{tag}</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      {/* Advanced Filters Panel */}
      {showFilters && (
        <View style={styles.filtersPanel}>
          {/* Quand */}
          <Text style={styles.filterSectionTitle}>📅 Quand</Text>
          <View style={styles.buttonsRow}>
            {["Aujourd'hui", "Ce weekend", "Choisir..."].map((option) => (
              <TouchableOpacity
                key={option}
                style={[
                  styles.filterOptionButton,
                  selectedWhen === option && styles.filterOptionButtonActive
                ]}
                onPress={() => setSelectedWhen(option)}
              >
                <Text style={[
                  styles.filterOptionButtonText,
                  selectedWhen === option && styles.filterOptionButtonTextActive
                ]}>
                  {option}
                </Text>
              </TouchableOpacity>
            ))}
          </View>

          {/* Lieu */}
          <View style={styles.locationHeader}>
            <Text style={styles.filterSectionTitle}>📍 Lieu</Text>
            <Text style={styles.locationRadius}>Rayon : {radius} km</Text>
          </View>
          <View style={styles.sliderContainer}>
            <View style={styles.sliderTrack}>
              <View style={[styles.sliderFill, { width: `${(radius / 100) * 100}%` }]} />
              <View style={[styles.sliderThumb, { left: `${(radius / 100) * 100}%` }]} />
            </View>
            <View style={styles.sliderLabels}>
              <Text style={styles.sliderLabel}>1km</Text>
              <Text style={styles.sliderLabel}>50km</Text>
              <Text style={styles.sliderLabel}>100km+</Text>
            </View>
            <TouchableOpacity style={styles.locationInput}>
              <Ionicons name="location-outline" size={16} color="#475569" />
              <Text style={styles.locationInputText}>Lomé, Togo (Position Actuelle)</Text>
            </TouchableOpacity>
          </View>

          {/* Prix */}
          <Text style={styles.filterSectionTitle}>💵 Prix</Text>
          <View style={styles.buttonsRow}>
            {[
              { label: 'Gratuit', val: 'Gratuit' },
              { label: 'Payant', val: 'Paid' },
              { label: 'Premium', val: 'Premium' }
            ].map((option) => (
              <TouchableOpacity
                key={option.val}
                style={[
                  styles.filterOptionButton,
                  priceFilter === option.val && styles.filterOptionButtonActive
                ]}
                onPress={() => setPriceFilter(option.val)}
              >
                <Text style={[
                  styles.filterOptionButtonText,
                  priceFilter === option.val && styles.filterOptionButtonTextActive
                ]}>
                  {option.label}
                </Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>
      )}

      {/* Results Header */}
      <View style={styles.resultsHeader}>
        <Text style={styles.resultsTitle}>Résultats ({resultsCount})</Text>
        <TouchableOpacity style={styles.sortButton}>
          <Text style={styles.sortButtonText}>Trier par : Pertinence</Text>
          <Ionicons name="chevron-down" size={14} color="#2563eb" />
        </TouchableOpacity>
      </View>

      {/* Results List */}
      {isLoading ? (
        <View style={styles.loaderContainer}>
          <ActivityIndicator size="large" color="#2563eb" />
        </View>
      ) : events.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Text style={styles.emptyText}>Aucun événement ne correspond à vos filtres.</Text>
        </View>
      ) : (
        <FlatList
          data={events}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderEventItem}
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
  header: {
    backgroundColor: '#fff',
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 8,
  },
  searchBarContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f1f5f9',
    borderRadius: 99,
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
  filtersBar: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    paddingHorizontal: 16,
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  filterToggle: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#eff6ff',
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 99,
    marginRight: 8,
    borderWidth: 1,
    borderColor: '#bfdbfe',
  },
  filterToggleActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  filterToggleText: {
    color: '#1e3a8a',
    fontWeight: 'bold',
    marginLeft: 6,
    fontSize: 13,
  },
  filterToggleTextActive: {
    color: '#fff',
  },
  quickTags: {
    flexDirection: 'row',
  },
  tagButton: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    backgroundColor: '#f1f5f9',
    borderRadius: 99,
    marginRight: 8,
  },
  tagButtonText: {
    color: '#475569',
    fontSize: 13,
    fontWeight: '500',
  },
  filtersPanel: {
    backgroundColor: '#fff',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#e2e8f0',
  },
  filterSectionTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#0f172a',
    marginBottom: 8,
  },
  buttonsRow: {
    flexDirection: 'row',
    marginBottom: 16,
    justifyContent: 'space-between',
  },
  filterOptionButton: {
    flex: 1,
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#cbd5e1',
    borderRadius: 8,
    paddingVertical: 10,
    alignItems: 'center',
    marginHorizontal: 4,
  },
  filterOptionButtonActive: {
    backgroundColor: '#1e3a8a',
    borderColor: '#1e3a8a',
  },
  filterOptionButtonText: {
    color: '#475569',
    fontSize: 12,
    fontWeight: '600',
  },
  filterOptionButtonTextActive: {
    color: '#fff',
  },
  locationHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  locationRadius: {
    fontSize: 12,
    color: '#1e3a8a',
    fontWeight: 'bold',
  },
  sliderContainer: {
    marginBottom: 16,
  },
  sliderTrack: {
    height: 4,
    backgroundColor: '#e2e8f0',
    borderRadius: 2,
    position: 'relative',
    marginVertical: 10,
  },
  sliderFill: {
    height: '100%',
    backgroundColor: '#3b82f6',
    borderRadius: 2,
  },
  sliderThumb: {
    width: 16,
    height: 16,
    borderRadius: 8,
    backgroundColor: '#1e3a8a',
    position: 'absolute',
    top: -6,
    marginLeft: -8,
  },
  sliderLabels: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 12,
  },
  sliderLabel: {
    fontSize: 10,
    color: '#64748b',
  },
  locationInput: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
    borderWidth: 1,
    borderColor: '#cbd5e1',
    borderRadius: 8,
    padding: 10,
  },
  locationInputText: {
    color: '#475569',
    fontSize: 13,
    marginLeft: 6,
  },
  resultsHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 14,
  },
  resultsTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#0f172a',
  },
  sortButton: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  sortButtonText: {
    color: '#2563eb',
    fontSize: 13,
    fontWeight: '600',
    marginRight: 4,
  },
  listContent: {
    padding: 16,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    overflow: 'hidden',
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  imageContainer: {
    position: 'relative',
    height: 180,
  },
  image: {
    width: '100%',
    height: '100%',
    resizeMode: 'cover',
  },
  categoryBadge: {
    position: 'absolute',
    top: 12,
    left: 12,
    backgroundColor: '#2563eb',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 6,
  },
  categoryBadgeText: {
    color: '#fff',
    fontSize: 10,
    fontWeight: 'bold',
  },
  priceBadge: {
    position: 'absolute',
    bottom: 12,
    right: 12,
    backgroundColor: 'rgba(255,255,255,0.9)',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 6,
  },
  priceBadgeText: {
    color: '#0f172a',
    fontSize: 12,
    fontWeight: 'bold',
  },
  cardBody: {
    padding: 16,
  },
  title: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#0f172a',
    marginBottom: 6,
  },
  dateTime: {
    fontSize: 13,
    color: '#2563eb',
    fontWeight: '600',
    marginBottom: 4,
  },
  location: {
    fontSize: 13,
    color: '#64748b',
  },
  loaderContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 32,
  },
  emptyText: {
    color: '#64748b',
    textAlign: 'center',
    fontSize: 14,
  },
});
