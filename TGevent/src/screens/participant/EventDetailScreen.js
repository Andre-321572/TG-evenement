import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Image, ScrollView, TouchableOpacity, ActivityIndicator, Alert, Dimensions, Platform } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

const { width } = Dimensions.get('window');

export default function EventDetailScreen({ route, navigation }) {
  const { eventId } = route.params;
  const [event, setEvent] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    fetchEventDetails();
  }, [eventId]);

  const fetchEventDetails = async () => {
    try {
      const response = await apiClient.get(`/events/${eventId}`);
      if (response.data.status === 'success') {
        setEvent(response.data.event);
      }
    } catch (e) {
      console.error(e);
      Alert.alert('Erreur', 'Impossible de charger les détails de l\'événement.');
    } finally {
      setIsLoading(false);
    }
  };

  const handleBuyTicket = (billet) => {
    if (billet.quantite_disponible <= 0) {
      Alert.alert('Épuisé', 'Désolé, ce type de billet n\'est plus disponible.');
      return;
    }
    
    // Naviguer vers l'écran de paiement Stripe webview
    navigation.navigate('Checkout', {
      evenementId: event.id,
      billetId: billet.id,
    });
  };

  // Helper pour formater la date en français
  const formatDetailDate = (dateString) => {
    if (!dateString) return '';
    try {
      const date = new Date(dateString);
      const days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
      const months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
      return `${days[date.getDay()]} ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    } catch (e) {
      return dateString;
    }
  };

  if (isLoading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#1d4ed8" />
      </View>
    );
  }

  if (!event) {
    return (
      <View style={styles.errorContainer}>
        <Ionicons name="alert-circle-outline" size={48} color="#ef4444" />
        <Text style={styles.errorText}>Événement non trouvé.</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 40 }} showsVerticalScrollIndicator={false}>
      {/* Banner Image */}
      <View style={styles.imageContainer}>
        <Image source={{ uri: event.photo_url }} style={styles.image} />
        {/* Custom Back Button overlay */}
        <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
          <Ionicons name="arrow-back" size={22} color="#1e293b" />
        </TouchableOpacity>
      </View>

      <View style={styles.content}>
        <Text style={styles.category}>{event.categorie}</Text>
        <Text style={styles.title}>{event.titre}</Text>

        {/* Practical Infos Card */}
        <Text style={styles.sectionTitle}>Infos Pratiques</Text>
        <View style={styles.infoBox}>
          <View style={styles.infoRow}>
            <Ionicons name="calendar" size={18} color="#1d4ed8" style={styles.infoIcon} />
            <Text style={styles.infoText}>📅 Date : {formatDetailDate(event.date)} à {event.start_heure || 'TBA'}</Text>
          </View>
          <View style={styles.infoRow}>
            <Ionicons name="location" size={18} color="#1d4ed8" style={styles.infoIcon} />
            <Text style={styles.infoText}>📍 Lieu : {event.lieu}</Text>
          </View>
          {event.nom_proprietaire && (
            <View style={styles.infoRow}>
              <Ionicons name="person" size={18} color="#1d4ed8" style={styles.infoIcon} />
              <Text style={styles.infoText}>👤 Organisé par : {event.nom_proprietaire}</Text>
            </View>
          )}
        </View>

        {/* Description Section */}
        {event.description && (
          <>
            <Text style={styles.sectionTitle}>Description</Text>
            <Text style={styles.description}>{event.description}</Text>
          </>
        )}

        {/* Tickets Section */}
        <Text style={styles.sectionTitle}>Billets Disponibles</Text>
        {event.billets && event.billets.length > 0 ? (
          event.billets.map((billet) => (
            <View key={billet.id} style={styles.ticketCard}>
              <View style={styles.ticketInfo}>
                <Text style={styles.ticketType}>{billet.type}</Text>
                <Text style={styles.ticketDesc}>{billet.description || 'Accès général à l\'événement'}</Text>
                <Text style={styles.ticketAvailability}>
                  Reste : {billet.quantite_disponible} / {billet.quantite_totale}
                </Text>
              </View>
              <View style={styles.ticketAction}>
                <Text style={styles.ticketPrice}>{billet.prix} FCFA</Text>
                <TouchableOpacity
                  style={[
                    styles.buyButton,
                    billet.quantite_disponible <= 0 && styles.buyButtonDisabled,
                  ]}
                  onPress={() => handleBuyTicket(billet)}
                  disabled={billet.quantite_disponible <= 0}
                >
                  <Text style={[styles.buyButtonText, billet.quantite_disponible <= 0 && styles.buyButtonTextDisabled]}>
                    {billet.quantite_disponible <= 0 ? 'Complet' : 'Réserver'}
                  </Text>
                </TouchableOpacity>
              </View>
            </View>
          ))
        ) : (
          <Text style={styles.noTickets}>Aucun billet configuré pour cet événement.</Text>
        )}

        {/* Sponsors Section */}
        {event.sponsors && event.sponsors.length > 0 && (
          <>
            <Text style={styles.sectionTitle}>Sponsors Officiels</Text>
            <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.sponsorsList} contentContainerStyle={{ paddingRight: 20 }}>
              {event.sponsors.map((sponsor) => (
                <View key={sponsor.id} style={styles.sponsorCard}>
                  {sponsor.logo_url ? (
                    <Image source={{ uri: sponsor.logo_url }} style={styles.sponsorLogo} />
                  ) : (
                    <View style={styles.sponsorLogoPlaceholder}>
                      <Ionicons name="business" size={24} color="#94a3b8" />
                    </View>
                  )}
                  <Text style={styles.sponsorName} numberOfLines={1}>{sponsor.nom}</Text>
                </View>
              ))}
            </ScrollView>
          </>
        )}
      </View>
    </ScrollView>
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
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
    padding: 40,
  },
  errorText: {
    color: '#ef4444',
    fontSize: 16,
    fontWeight: 'bold',
    marginTop: 12,
  },
  imageContainer: {
    position: 'relative',
    width: width,
    height: 250,
  },
  image: {
    width: '100%',
    height: '100%',
    resizeMode: 'cover',
  },
  backButton: {
    position: 'absolute',
    top: Platform.OS === 'ios' ? 50 : 20,
    left: 20,
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: 'rgba(255, 255, 255, 0.9)',
    alignItems: 'center',
    justifyContent: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 2,
  },
  content: {
    padding: 20,
  },
  category: {
    fontSize: 11,
    fontWeight: 'bold',
    color: '#1d4ed8',
    textTransform: 'uppercase',
    letterSpacing: 1.2,
    marginBottom: 6,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#0f172a',
    lineHeight: 30,
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 17,
    fontWeight: 'bold',
    color: '#0f172a',
    marginTop: 24,
    marginBottom: 12,
  },
  infoBox: {
    backgroundColor: '#ffffff',
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: '#f1f5f9',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 8,
    elevation: 2,
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 10,
  },
  infoIcon: {
    marginRight: 10,
  },
  infoText: {
    color: '#334155',
    fontSize: 14,
    fontWeight: '500',
  },
  description: {
    color: '#475569',
    fontSize: 14,
    lineHeight: 22,
  },
  ticketCard: {
    backgroundColor: '#ffffff',
    borderRadius: 16,
    padding: 16,
    marginBottom: 12,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#f1f5f9',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 6,
    elevation: 2,
  },
  ticketInfo: {
    flex: 1,
    marginRight: 16,
  },
  ticketType: {
    color: '#0f172a',
    fontSize: 16,
    fontWeight: 'bold',
  },
  ticketDesc: {
    color: '#64748b',
    fontSize: 12,
    marginTop: 4,
    lineHeight: 16,
  },
  ticketAvailability: {
    color: '#16a34a',
    fontSize: 11,
    marginTop: 6,
    fontWeight: '600',
  },
  ticketAction: {
    alignItems: 'flex-end',
  },
  ticketPrice: {
    color: '#ef4444',
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 8,
  },
  buyButton: {
    backgroundColor: '#fee2e2',
    borderRadius: 8,
    paddingHorizontal: 16,
    paddingVertical: 8,
  },
  buyButtonDisabled: {
    backgroundColor: '#f1f5f9',
  },
  buyButtonText: {
    color: '#ef4444',
    fontWeight: 'bold',
    fontSize: 13,
  },
  buyButtonTextDisabled: {
    color: '#94a3b8',
  },
  noTickets: {
    color: '#94a3b8',
    fontStyle: 'italic',
  },
  sponsorsList: {
    flexDirection: 'row',
    marginTop: 4,
  },
  sponsorCard: {
    backgroundColor: '#ffffff',
    borderRadius: 12,
    padding: 10,
    alignItems: 'center',
    marginRight: 10,
    width: 90,
    borderWidth: 1,
    borderColor: '#f1f5f9',
  },
  sponsorLogo: {
    width: 50,
    height: 50,
    borderRadius: 25,
    resizeMode: 'contain',
  },
  sponsorLogoPlaceholder: {
    width: 50,
    height: 50,
    borderRadius: 25,
    backgroundColor: '#f1f5f9',
    alignItems: 'center',
    justifyContent: 'center',
  },
  sponsorName: {
    color: '#334155',
    fontSize: 10,
    fontWeight: '600',
    marginTop: 6,
    textAlign: 'center',
    width: '100%',
  },
});
