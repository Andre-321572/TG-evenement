import React, { useState, useRef, useEffect } from 'react';
import { StyleSheet, View, Text, TouchableOpacity, TextInput, ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView, Animated, Easing } from 'react-native';
import { useStripe } from '@stripe/stripe-react-native';
import { Ionicons } from '@expo/vector-icons';
import apiClient from '../../api/client';

export default function CheckoutScreen({ route, navigation }) {
  const { evenementId, billetId, user, quantity } = route.params;
  const { initPaymentSheet, presentPaymentSheet } = useStripe();

  const [paymentMethod, setPaymentMethod] = useState('moov_money');
  const [phone, setPhone] = useState('');
  const [loading, setLoading] = useState(false);

  // État pour l'écran d'attente Mobile Money (polling)
  const [waitingForMobileMoney, setWaitingForMobileMoney] = useState(false);
  const [pendingTransactionId, setPendingTransactionId] = useState(null);
  const [pendingPaymentData, setPendingPaymentData] = useState(null);
  const [waitingMessage, setWaitingMessage] = useState('Veuillez valider le paiement sur votre téléphone...');

  // Animation pour l'écran d'attente
  const pulseAnim = useRef(new Animated.Value(1)).current;
  const rotateAnim = useRef(new Animated.Value(0)).current;
  const pollingRef = useRef(null);

  // Lancer l'animation de pulse + rotation quand on attend le paiement
  useEffect(() => {
    if (waitingForMobileMoney) {
      // Pulse animation
      const pulse = Animated.loop(
        Animated.sequence([
          Animated.timing(pulseAnim, {
            toValue: 1.15,
            duration: 1000,
            easing: Easing.inOut(Easing.ease),
            useNativeDriver: true,
          }),
          Animated.timing(pulseAnim, {
            toValue: 1,
            duration: 1000,
            easing: Easing.inOut(Easing.ease),
            useNativeDriver: true,
          }),
        ])
      );
      pulse.start();

      // Rotation animation
      const rotate = Animated.loop(
        Animated.timing(rotateAnim, {
          toValue: 1,
          duration: 3000,
          easing: Easing.linear,
          useNativeDriver: true,
        })
      );
      rotate.start();

      return () => {
        pulse.stop();
        rotate.stop();
      };
    }
  }, [waitingForMobileMoney]);

  // Cleanup polling on unmount
  useEffect(() => {
    return () => {
      if (pollingRef.current) {
        clearInterval(pollingRef.current);
      }
    };
  }, []);

  // =========================================================================
  // STRIPE : Paiement natif via Payment Sheet
  // =========================================================================
  const handleStripeCheckout = async () => {
    setLoading(true);
    try {
      const payload = {
        evenement_id: evenementId,
        billet_id: billetId,
        quantity: quantity || 1,
        payment_method: 'stripe',
        phone: phone,
      };
      if (user) {
        payload.user_id = user.id;
        payload.email = user.email;
        payload.name = user.prenom + ' ' + user.nom;
      }

      // 1. Demander un PaymentIntent au backend
      const response = await apiClient.post('/checkout', payload);
      const data = response.data;

      if (data.status !== 'success' || !data.client_secret) {
        Alert.alert('Erreur', data.message || 'Impossible d\'initialiser le paiement Stripe.');
        return;
      }

      // 2. Initialiser la Payment Sheet native
      const { error: initError } = await initPaymentSheet({
        paymentIntentClientSecret: data.client_secret,
        customerEphemeralKeySecret: data.ephemeral_key,
        customerId: data.customer_id,
        merchantDisplayName: 'TGevent',
        allowsDelayedPaymentMethods: false,
      });

      if (initError) {
        Alert.alert('Erreur', initError.message);
        return;
      }

      // 3. Afficher la Payment Sheet native (le bel écran de carte bancaire)
      const { error: presentError } = await presentPaymentSheet();

      if (presentError) {
        if (presentError.code === 'Canceled') {
          // L'utilisateur a fermé la Payment Sheet, pas d'alerte
          return;
        }
        Alert.alert('Erreur de paiement', presentError.message);
        return;
      }

      // 4. Paiement réussi côté Stripe ! Confirmer côté backend et générer les tickets
      setLoading(true);
      const confirmResponse = await apiClient.post('/payment/status', {
        transaction_id: data.transaction_id,
        payment_type: 'stripe_native',
        evenement_id: evenementId,
        billet_id: billetId,
        quantity: quantity || 1,
        user_id: user?.id,
        email: user?.email,
        name: user ? user.prenom + ' ' + user.nom : undefined,
      });

      if (confirmResponse.data.status === 'paid') {
        Alert.alert(
          '🎉 Paiement Réussi !',
          'Félicitations ! Votre billet a été acheté avec succès.',
          [{ text: 'Voir mes billets', onPress: () => navigation.navigate('Billets') }]
        );
      } else {
        Alert.alert('Information', confirmResponse.data.message || 'Le paiement est en cours de traitement.');
      }

    } catch (error) {
      console.error('Stripe checkout error:', error.response?.data || error.message);
      Alert.alert('Erreur', error.response?.data?.message || 'Une erreur est survenue.');
    } finally {
      setLoading(false);
    }
  };

  // =========================================================================
  // MOBILE MONEY : Paiement natif avec écran d'attente + polling
  // =========================================================================
  const handleMobileMoneyCheckout = async () => {
    if (!phone) {
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
      const data = response.data;

      if (data.status !== 'success') {
        Alert.alert('Erreur', data.message || 'Impossible d\'initialiser le paiement.');
        return;
      }

      // Sauvegarder les données pour le polling
      setPendingTransactionId(data.transaction_id);
      setPendingPaymentData({
        transaction_id: data.transaction_id,
        payment_type: 'mobile_money',
        evenement_id: evenementId,
        billet_id: billetId,
        quantity: quantity || 1,
        user_id: user?.id,
        email: user?.email,
        name: user ? user.prenom + ' ' + user.nom : undefined,
      });

      // Afficher l'écran d'attente animé
      setWaitingForMobileMoney(true);
      setWaitingMessage(
        paymentMethod === 'moov_money'
          ? 'Veuillez taper votre code secret Moov Money sur votre téléphone...'
          : 'Veuillez valider le paiement TMoney sur votre téléphone...'
      );

      // Démarrer le polling toutes les 4 secondes
      startPolling(data.transaction_id);

    } catch (error) {
      console.error('Mobile Money checkout error:', error.response?.data || error.message);
      Alert.alert('Erreur', error.response?.data?.message || 'Une erreur est survenue.');
    } finally {
      setLoading(false);
    }
  };

  // Polling : vérifier le statut du paiement toutes les 4 secondes
  const startPolling = (transactionId) => {
    let attempts = 0;
    const maxAttempts = 45; // 45 x 4s = 3 minutes max

    pollingRef.current = setInterval(async () => {
      attempts++;

      if (attempts > maxAttempts) {
        clearInterval(pollingRef.current);
        pollingRef.current = null;
        setWaitingForMobileMoney(false);
        Alert.alert(
          'Délai expiré',
          'Le paiement n\'a pas été confirmé dans le temps imparti. Veuillez réessayer.',
          [{ text: 'OK', onPress: () => navigation.goBack() }]
        );
        return;
      }

      try {
        const statusPayload = {
          transaction_id: transactionId,
          payment_type: 'mobile_money',
          evenement_id: evenementId,
          billet_id: billetId,
          quantity: quantity || 1,
          user_id: user?.id,
          email: user?.email,
          name: user ? user.prenom + ' ' + user.nom : undefined,
        };

        const statusResponse = await apiClient.post('/payment/status', statusPayload);
        const statusData = statusResponse.data;

        if (statusData.status === 'paid') {
          // SUCCÈS ! Arrêter le polling et afficher le message de réussite
          clearInterval(pollingRef.current);
          pollingRef.current = null;
          setWaitingForMobileMoney(false);

          Alert.alert(
            '🎉 Paiement Réussi !',
            'Félicitations ! Votre billet a été acheté avec succès.',
            [{ text: 'Voir mes billets', onPress: () => navigation.navigate('Billets') }]
          );
        } else if (statusData.status === 'failed') {
          // ÉCHEC !
          clearInterval(pollingRef.current);
          pollingRef.current = null;
          setWaitingForMobileMoney(false);

          Alert.alert(
            'Paiement Échoué',
            statusData.message || 'Le paiement a échoué. Veuillez réessayer.',
            [{ text: 'Réessayer', onPress: () => {} }]
          );
        }
        // Si 'pending', on continue le polling silencieusement
      } catch (error) {
        // On continue le polling malgré l'erreur réseau temporaire
        console.log('Polling error (retrying):', error.message);
      }
    }, 4000);
  };

  // Annuler l'attente du paiement Mobile Money
  const cancelMobileMoneyWait = () => {
    if (pollingRef.current) {
      clearInterval(pollingRef.current);
      pollingRef.current = null;
    }
    setWaitingForMobileMoney(false);
    setPendingTransactionId(null);
    setPendingPaymentData(null);
  };

  // Handler principal : dispatcher selon la méthode choisie
  const handleCheckout = () => {
    if (paymentMethod === 'stripe') {
      handleStripeCheckout();
    } else {
      handleMobileMoneyCheckout();
    }
  };

  // =========================================================================
  // ÉCRAN D'ATTENTE MOBILE MONEY (100% natif, pas de WebView !)
  // =========================================================================
  if (waitingForMobileMoney) {
    const rotateInterpolate = rotateAnim.interpolate({
      inputRange: [0, 1],
      outputRange: ['0deg', '360deg'],
    });

    return (
      <View style={styles.waitingContainer}>
        {/* Cercles décoratifs en arrière-plan */}
        <View style={styles.bgCircle1} />
        <View style={styles.bgCircle2} />

        <Animated.View style={[styles.waitingIconContainer, { transform: [{ scale: pulseAnim }] }]}>
          <View style={styles.waitingIconInner}>
            <Ionicons
              name={paymentMethod === 'moov_money' ? 'phone-portrait' : 'phone-portrait'}
              size={48}
              color="#fff"
            />
          </View>
        </Animated.View>

        <Text style={styles.waitingTitle}>Paiement en cours</Text>
        <Text style={styles.waitingMessage}>{waitingMessage}</Text>

        <View style={styles.waitingStepsContainer}>
          <View style={styles.waitingStep}>
            <View style={[styles.stepDot, styles.stepDotActive]} />
            <Text style={styles.stepText}>Transaction initiée</Text>
          </View>
          <View style={styles.stepLine} />
          <View style={styles.waitingStep}>
            <Animated.View style={[styles.stepDot, styles.stepDotPending, { transform: [{ rotate: rotateInterpolate }] }]}>
              <ActivityIndicator size="small" color="#4f46e5" />
            </Animated.View>
            <Text style={styles.stepText}>En attente de validation</Text>
          </View>
          <View style={styles.stepLine} />
          <View style={styles.waitingStep}>
            <View style={styles.stepDot} />
            <Text style={[styles.stepText, styles.stepTextInactive]}>Billet généré</Text>
          </View>
        </View>

        <Text style={styles.waitingHint}>
          {paymentMethod === 'moov_money'
            ? '📱 Un message USSD va s\'afficher sur votre téléphone. Tapez votre code secret pour valider.'
            : '📱 Validez le paiement depuis l\'application TMoney sur votre téléphone.'}
        </Text>

        <TouchableOpacity style={styles.cancelBtn} onPress={cancelMobileMoneyWait}>
          <Text style={styles.cancelBtnText}>Annuler</Text>
        </TouchableOpacity>
      </View>
    );
  }

  // =========================================================================
  // ÉCRAN PRINCIPAL : Choix du moyen de paiement
  // =========================================================================
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
            <Text style={styles.payBtnText}>
              {paymentMethod === 'stripe' ? 'Payer par carte bancaire' : 'Payer maintenant'}
            </Text>
          )}
        </TouchableOpacity>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0f172a',
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

  // ==========================================
  // STYLES ÉCRAN D'ATTENTE MOBILE MONEY
  // ==========================================
  waitingContainer: {
    flex: 1,
    backgroundColor: '#0f172a',
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 30,
  },
  bgCircle1: {
    position: 'absolute',
    top: -80,
    right: -80,
    width: 250,
    height: 250,
    borderRadius: 125,
    backgroundColor: 'rgba(79, 70, 229, 0.08)',
  },
  bgCircle2: {
    position: 'absolute',
    bottom: -60,
    left: -60,
    width: 200,
    height: 200,
    borderRadius: 100,
    backgroundColor: 'rgba(16, 185, 129, 0.06)',
  },
  waitingIconContainer: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: 'rgba(79, 70, 229, 0.15)',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 30,
  },
  waitingIconInner: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#4f46e5',
    alignItems: 'center',
    justifyContent: 'center',
  },
  waitingTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#f8fafc',
    marginBottom: 10,
  },
  waitingMessage: {
    fontSize: 16,
    color: '#94a3b8',
    textAlign: 'center',
    marginBottom: 40,
    lineHeight: 24,
  },
  waitingStepsContainer: {
    alignItems: 'center',
    marginBottom: 40,
  },
  waitingStep: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  stepDot: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: '#334155',
    alignItems: 'center',
    justifyContent: 'center',
  },
  stepDotActive: {
    backgroundColor: '#10b981',
  },
  stepDotPending: {
    backgroundColor: 'rgba(79, 70, 229, 0.2)',
    borderWidth: 2,
    borderColor: '#4f46e5',
  },
  stepLine: {
    width: 2,
    height: 20,
    backgroundColor: '#334155',
    marginLeft: 13,
  },
  stepText: {
    fontSize: 14,
    color: '#f8fafc',
    fontWeight: '500',
  },
  stepTextInactive: {
    color: '#64748b',
  },
  waitingHint: {
    fontSize: 13,
    color: '#64748b',
    textAlign: 'center',
    lineHeight: 20,
    paddingHorizontal: 10,
    marginBottom: 30,
  },
  cancelBtn: {
    paddingVertical: 14,
    paddingHorizontal: 40,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: '#ef4444',
  },
  cancelBtnText: {
    color: '#ef4444',
    fontSize: 15,
    fontWeight: '600',
  },
});
