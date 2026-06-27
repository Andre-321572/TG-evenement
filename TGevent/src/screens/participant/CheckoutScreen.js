import React, { useRef } from 'react';
import { StyleSheet, View, ActivityIndicator, Alert } from 'react-native';
import { WebView } from 'react-native-webview';
import { API_URL } from '../../api/client';

export default function CheckoutScreen({ route, navigation }) {
  const { evenementId, billetId } = route.params;
  const webViewRef = useRef(null);

  // Conversion de l'url d'API (ex: http://10.0.2.2:8000/api) en URL de base web (ex: http://10.0.2.2:8000)
  const baseUrl = API_URL.replace('/api', '');

  // Charger le process de paiement du Laravel web en injectant les IDs en paramètres GET
  const checkoutUrl = `${baseUrl}/p/payement/process?evenement_id=${evenementId}&billet_id=${billetId}`;

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
              // Naviguer vers la liste des tickets
              navigation.navigate('Mes Tickets');
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
            onPress: () => navigation.goBack(),
          },
        ]
      );
    }
  };

  return (
    <View style={styles.container}>
      <WebView
        ref={webViewRef}
        source={{ uri: checkoutUrl }}
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

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#111827',
  },
  loading: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#111827',
  },
});
