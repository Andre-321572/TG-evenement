import React, { useState, useRef } from 'react';
import { StyleSheet, View, Text, TouchableOpacity, TextInput, ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView, Image } from 'react-native';
import { WebView } from 'react-native-webview';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function CheckoutScreen({ route, navigation }) {
  const { evenementId, billetId, user, quantity } = route.params;
  const webViewRef = useRef(null);

  const [paymentMethod, setPaymentMethod] = useState('moov_money'); // default
  const [phone, setPhone] = useState('');
  const [loading, setLoading] = useState(false);
  const [paymentUrl, setPaymentUrl] = useState(null);

  const handleCheckout = async () => {
    if ((paymentMethod === 'moov_money' || paymentMethod === 'mix_by_yas') && !phone) {
      Alert.alert('Erreur', 'Veuillez saisir votre numéro de téléphone.');
      return;
    }

    setLoading(true);
    try {
      const payload = {
        evenement_id: evenementId,
        billet_id: billetId,
        quantity: quantity || 1,
        payment_method: paymentMethod,
        phone: phone,
      };

      if (user) {
        payload.user_id = user.id;
        payload.email = user.email;
        payload.name = user.prenom + ' ' + user.nom;
      }

      const response = await apiClient.post('/checkout', payload);
      
      if (response.data.status === 'success' && response.data.payment_url) {
        setPaymentUrl(response.data.payment_url);
      } else {
        Alert.alert('Erreur', response.data.message || 'Impossible d\'initialiser le paiement.');
      }
    } catch (error) {
      console.error('Checkout error:', error.response?.data || error.message);
      Alert.alert('Erreur', error.response?.data?.message || 'Une erreur est survenue lors de la communication avec le serveur.');
    } finally {
      setLoading(false);
    }
  };

  const handleNavigationStateChange = (navState) => {
    const { url } = navState;

    // Détecter la redirection de succès
    if (url.includes('/payement/success')) {
      Alert.alert(
        'Paiement Réussi',
        'Félicitations ! Votre billet a été acheté avec succès.',
        [
          {
            text: 'Voir mes billets',
            onPress: () => {
              navigation.navigate('Billets');
            },
          },
        ]
      );
    }

    // Détecter la redirection d'annulation
    if (url.includes('/payement/cancel')) {
      Alert.alert(
        'Paiement Annulé',
        'Vous avez annulé la transaction.',
        [
          {
            text: 'Retour',
            onPress: () => {
              setPaymentUrl(null); // Close webview
              navigation.goBack();
            },
          },
        ]
      );
    }
  };

  // Si on a l'URL de paiement, on affiche uniquement la WebView
  if (paymentUrl) {
    return (
      <View style={styles.container}>
        <WebView
          ref={webViewRef}
          source={{ uri: paymentUrl }}
          onNavigationStateChange={handleNavigationStateChange}
          startInLoadingState={true}
          renderLoading={() => (
            <View style={styles.loading}>
              <ActivityIndicator size="large" color="#10b981" />
            </View>
          )}
        />
      </View>
    );
  }

  return (
    <KeyboardAvoidingView 
      style={styles.container} 
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
    >
      <ScrollView contentContainerStyle={styles.scrollContent}>
        
        <View style={styles.header}>
          <Text style={styles.title}>Finalisez votre achat</Text>
          <Text style={styles.subtitle}>Choisissez votre moyen de paiement sécurisé</Text>
        </View>

        <View style={styles.methodsContainer}>
          {/* Option Moov */}
          <TouchableOpacity 
            style={[styles.methodCard, paymentMethod === 'moov_money' && styles.methodCardActive]} 
            onPress={() => setPaymentMethod('moov_money')}
          >
            <View style={styles.methodIconContainer}>
              <Ionicons name="phone-portrait-outline" size={24} color={paymentMethod === 'moov_money' ? '#4f46e5' : '#64748b'} />
            </View>
            <Text style={[styles.methodText, paymentMethod === 'moov_money' && styles.methodTextActive]}>Moov Money</Text>
            {paymentMethod === 'moov_money' && <Ionicons name="checkmark-circle" size={24} color="#10b981" style={styles.checkIcon} />}
          </TouchableOpacity>

          {/* Option Mix by Yas */}
          <TouchableOpacity 
            style={[styles.methodCard, paymentMethod === 'mix_by_yas' && styles.methodCardActive]} 
            onPress={() => setPaymentMethod('mix_by_yas')}
          >
            <View style={styles.methodIconContainer}>
              <Ionicons name="phone-portrait-outline" size={24} color={paymentMethod === 'mix_by_yas' ? '#4f46e5' : '#64748b'} />
            </View>
            <Text style={[styles.methodText, paymentMethod === 'mix_by_yas' && styles.methodTextActive]}>Mix by Yas (TMoney)</Text>
            {paymentMethod === 'mix_by_yas' && <Ionicons name="checkmark-circle" size={24} color="#10b981" style={styles.checkIcon} />}
          </TouchableOpacity>

          {/* Option Stripe */}
          <TouchableOpacity 
            style={[styles.methodCard, paymentMethod === 'stripe' && styles.methodCardActive]} 
            onPress={() => setPaymentMethod('stripe')}
          >
            <View style={styles.methodIconContainer}>
              <Ionicons name="card-outline" size={24} color={paymentMethod === 'stripe' ? '#4f46e5' : '#64748b'} />
            </View>
            <Text style={[styles.methodText, paymentMethod === 'stripe' && styles.methodTextActive]}>Carte Bancaire (Stripe)</Text>
            {paymentMethod === 'stripe' && <Ionicons name="checkmark-circle" size={24} color="#10b981" style={styles.checkIcon} />}
          </TouchableOpacity>
        </View>

        {/* Champs conditionnels */}
        {(paymentMethod === 'moov_money' || paymentMethod === 'mix_by_yas') && (
          <View style={styles.inputContainer}>
            <Text style={styles.inputLabel}>Numéro avec lequel vous voulez payer :</Text>
            <View style={styles.inputWrapper}>
              <Ionicons name="call-outline" size={20} color="#94a3b8" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Ex: 99 12 34 56"
                placeholderTextColor="#94a3b8"
                keyboardType="phone-pad"
                value={phone}
                onChangeText={setPhone}
              />
            </View>
          </View>
        )}

      </ScrollView>

      {/* Footer Bouton */}
      <View style={styles.footer}>
        <TouchableOpacity 
          style={styles.payBtn} 
          onPress={handleCheckout} 
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.payBtnText}>Continuer vers le paiement</Text>
          )}
        </TouchableOpacity>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0f172a', // Dark theme background matching TGevent
  },
  scrollContent: {
    padding: 20,
    paddingBottom: 40,
  },
  header: {
    marginBottom: 30,
    marginTop: 10,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#f8fafc',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 14,
    color: '#94a3b8',
  },
  methodsContainer: {
    gap: 12,
    marginBottom: 30,
  },
  methodCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#1e293b',
    padding: 16,
    borderRadius: 16,
    borderWidth: 2,
    borderColor: 'transparent',
  },
  methodCardActive: {
    borderColor: '#4f46e5',
    backgroundColor: 'rgba(79, 70, 229, 0.1)',
  },
  methodIconContainer: {
    width: 40,
    height: 40,
    borderRadius: 10,
    backgroundColor: '#334155',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 15,
  },
  methodText: {
    fontSize: 16,
    color: '#cbd5e1',
    fontWeight: '600',
    flex: 1,
  },
  methodTextActive: {
    color: '#f8fafc',
  },
  checkIcon: {
    marginLeft: 'auto',
  },
  inputContainer: {
    backgroundColor: '#1e293b',
    padding: 16,
    borderRadius: 16,
  },
  inputLabel: {
    color: '#94a3b8',
    fontSize: 14,
    marginBottom: 10,
    fontWeight: '600',
  },
  inputWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#0f172a',
    borderRadius: 12,
    paddingHorizontal: 15,
    borderWidth: 1,
    borderColor: '#334155',
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    color: '#f8fafc',
    fontSize: 16,
    paddingVertical: 15,
  },
  footer: {
    padding: 20,
    backgroundColor: '#1e293b',
    borderTopWidth: 1,
    borderTopColor: '#334155',
  },
  payBtn: {
    backgroundColor: '#4f46e5',
    paddingVertical: 16,
    borderRadius: 16,
    alignItems: 'center',
    justifyContent: 'center',
  },
  payBtnText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  loading: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#0f172a',
  },
});
