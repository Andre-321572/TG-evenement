import React, { useState, useEffect, useContext } from 'react';
import { StyleSheet, Text, View, Image, ScrollView, TouchableOpacity, ActivityIndicator, Alert, Dimensions } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';
import { AuthContext } from '../../context/AuthContext';

const { width } = Dimensions.get('window');

export default function EventDetailScreen({ route, navigation }) {
  const { eventId } = route.params;
  const { token } = useContext(AuthContext);
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
    if (!token) {
      Alert.alert(
        'Connexion requise',
        'Veuillez vous connecter à votre compte pour acheter des billets.',
        [
          { text: 'Annuler', style: 'cancel' },
          { text: 'Se connecter', onPress: () => navigation.navigate('Login') }
        ]
      );
      return;
    }

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

  if (isLoading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#2563eb" />
      </View>
    );
  }

  if (!event) {
    return (
      <View style={styles.errorContainer}>
        <Text style={styles.errorText}>Événement non trouvé.</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 40 }} showsVerticalScrollIndicator={false}>
      {/* Background Image of Event */}
      <Image source={{ uri: event.photo_url }} style={styles.image} />
      
      {/* Detail Content */}
      <View style={styles.content}>
        <Text style={styles.category}>{event.categorie}</Text>
        <Text style={styles.title}>{event.titre}</Text>

        <Text style={styles.sectionTitle}>Infos Pratiques</Text>
        <View style={styles.infoBox}>
          <Text style={styles.infoText}>📅 Date : {event.date} à {event.start_heure}</Text>
          <Text style={styles.infoText}>📍 Lieu : {event.lieu}</Text>
          {event.nom_proprietaire && (
            <Text style={styles.infoText}>👤 Organisé par : {event.nom_proprietaire}</Text>
          )}
        </View>

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
                <Text style={styles.ticketDesc}>{billet.description || 'Accès standard à l\'événement'}</Text>
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
                  <Text style={styles.buyButtonText}>
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
            <Text style={styles.sectionTitle}>Sponsors</Text>
            <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.sponsorsList}>
              {event.sponsors.map((sponsor) => (
                <View key={sponsor.id} style={styles.sponsorCard}>
                  {sponsor.logo_url ? (
                    <Image source={{ uri: sponsor.logo_url }} style={styles.sponsorLogo} />
                  ) : (
                    <View style={styles.sponsorLogoPlaceholder} />
                  )}
                  <Text style={styles.sponsorName}>{sponsor.nom}</Text>
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
  },
  errorText: {
    color: '#ef4444',
    fontSize: 16,
    fontWeight: 'bold',
  },
  image: {
    width: width,
    height: 250,
    resizeMode: 'cover',
  },
  content: {
    padding: 20,
  },
  category: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#2563eb',
    textTransform: 'uppercase',
    letterSpacing: 1,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginTop: 6,
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginTop: 24,
    marginBottom: 12,
  },
  infoBox: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  infoText: {
    color: '#475569',
    fontSize: 15,
    marginBottom: 8,
  },
  description: {
    color: '#64748b',
    fontSize: 15,
    lineHeight: 22,
  },
  ticketCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
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
  },
  ticketAvailability: {
    color: '#2563eb',
    fontSize: 11,
    marginTop: 6,
    fontWeight: '600',
  },
  ticketAction: {
    alignItems: 'flex-end',
  },
  ticketPrice: {
    color: '#0f172a',
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 8,
  },
  buyButton: {
    backgroundColor: '#2563eb',
    borderRadius: 8,
    paddingHorizontal: 16,
    paddingVertical: 8,
  },
  buyButtonDisabled: {
    backgroundColor: '#94a3b8',
  },
  buyButtonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 14,
  },
  noTickets: {
    color: '#64748b',
    fontStyle: 'italic',
  },
  sponsorsList: {
    flexDirection: 'row',
    marginTop: 8,
  },
  sponsorCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    alignItems: 'center',
    marginRight: 10,
    width: 100,
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  sponsorLogo: {
    width: 60,
    height: 60,
    borderRadius: 30,
    resizeMode: 'contain',
    backgroundColor: '#fff',
  },
  sponsorLogoPlaceholder: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: '#f1f5f9',
  },
  sponsorName: {
    color: '#475569',
    fontSize: 11,
    marginTop: 6,
    textAlign: 'center',
  },
});
