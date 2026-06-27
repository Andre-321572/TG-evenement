import React, { useContext } from 'react';
import { View, ActivityIndicator, Text } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../context/AuthContext';

// Import des écrans (à créer ensuite)
import LoginScreen from '../screens/shared/LoginScreen';
import RegisterScreen from '../screens/shared/RegisterScreen';

// Participant screens
import EventListScreen from '../screens/participant/EventListScreen';
import EventDetailScreen from '../screens/participant/EventDetailScreen';
import CheckoutScreen from '../screens/participant/CheckoutScreen';
import TicketListScreen from '../screens/participant/TicketListScreen';
import SearchScreen from '../screens/participant/SearchScreen';
import NotificationsScreen from '../screens/shared/NotificationsScreen';

// Scanner screens
import ScannerScreen from '../screens/scanner/ScannerScreen';
import ScanStatsScreen from '../screens/scanner/ScanStatsScreen';

// Organizer screens
import OrgDashboardScreen from '../screens/organizer/OrgDashboardScreen';
import OrgCreateEventScreen from '../screens/organizer/OrgCreateEventScreen';

// Profil screen générique de déconnexion
import ProfileScreen from '../screens/shared/ProfileScreen';

const Stack = createStackNavigator();
const Tab = createBottomTabNavigator();

// Stack de navigation pour l'exploration des événements (Participant)
const ExploreStack = () => (
  <Stack.Navigator
    screenOptions={{
      headerStyle: { backgroundColor: '#ffffff', borderBottomWidth: 1, borderBottomColor: '#e2e8f0' },
      headerTintColor: '#0f172a',
      headerTitleStyle: { fontWeight: 'bold' },
    }}
  >
    <Stack.Screen name="EventList" component={EventListScreen} options={{ headerShown: false }} />
    <Stack.Screen name="EventDetail" component={EventDetailScreen} options={{ title: 'Détails de l\'événement' }} />
    <Stack.Screen name="Checkout" component={CheckoutScreen} options={{ title: 'Paiement Sécurisé' }} />
  </Stack.Navigator>
);

// Navigation Participant (Tab)
const ParticipantTabNavigator = () => (
  <Tab.Navigator
    screenOptions={({ route }) => ({
      headerShown: false,
      tabBarShowLabel: false,
      tabBarStyle: {
        backgroundColor: '#ffffff',
        borderTopWidth: 1,
        borderTopColor: '#e2e8f0',
        height: 75,
        paddingBottom: 12,
        paddingTop: 12,
      },
      tabBarIcon: ({ focused }) => {
        let iconName;
        let label;
        let pillColor;
        let activeColor;

        if (route.name === 'Accueil') {
          iconName = focused ? 'home' : 'home-outline';
          label = 'Accueil';
          pillColor = '#fee2e2'; // light red
          activeColor = '#ef4444'; // red
        } else if (route.name === 'Recherche') {
          iconName = focused ? 'search' : 'search-outline';
          label = 'Recherche';
          pillColor = '#e0e7ff'; // light indigo
          activeColor = '#1d4ed8'; // indigo
        } else if (route.name === 'Mes Billets') {
          iconName = focused ? 'ticket' : 'ticket-outline';
          label = 'Billets';
          pillColor = '#e0e7ff';
          activeColor = '#1d4ed8';
        } else if (route.name === 'Notifications') {
          iconName = focused ? 'notifications' : 'notifications-outline';
          label = 'Notifications';
          pillColor = '#e0e7ff';
          activeColor = '#1d4ed8';
        } else if (route.name === 'Profil') {
          iconName = focused ? 'person' : 'person-outline';
          label = 'Profil';
          pillColor = '#e0e7ff';
          activeColor = '#1d4ed8';
        }

        if (focused) {
          return (
            <View style={{
              flexDirection: 'row',
              alignItems: 'center',
              backgroundColor: pillColor,
              paddingHorizontal: 12,
              paddingVertical: 6,
              borderRadius: 20,
              gap: 4
            }}>
              <Ionicons name={iconName} size={18} color={activeColor} />
              <Text style={{ color: activeColor, fontWeight: 'bold', fontSize: 11 }}>{label}</Text>
            </View>
          );
        }

        return <Ionicons name={iconName} size={22} color="#94a3b8" />;
      }
    })}
  >
    <Tab.Screen name="Accueil" component={ExploreStack} />
    <Tab.Screen name="Recherche" component={SearchScreen} />
    <Tab.Screen name="Mes Billets" component={TicketListScreen} />
    <Tab.Screen name="Notifications" component={NotificationsScreen} />
    <Tab.Screen name="Profil" component={ProfileScreen} />
  </Tab.Navigator>
);

// Navigation Scanner (Tab)
const ScannerTabNavigator = () => (
  <Tab.Navigator
    screenOptions={{
      tabBarStyle: { backgroundColor: '#111827' },
      tabBarActiveTintColor: '#3b82f6',
      tabBarInactiveTintColor: '#9ca3af',
      headerStyle: { backgroundColor: '#1f2937' },
      headerTintColor: '#fff',
    }}
  >
    <Tab.Screen name="Scanner Billet" component={ScannerScreen} options={{ title: 'Scan Caméra' }} />
    <Tab.Screen name="Statistiques" component={ScanStatsScreen} options={{ title: 'Stats de Scan' }} />
    <Tab.Screen name="Profil" component={ProfileScreen} />
  </Tab.Navigator>
);

// Navigation Organisateur (Tab)
const OrganizerTabNavigator = () => (
  <Tab.Navigator
    screenOptions={{
      tabBarStyle: { backgroundColor: '#111827' },
      tabBarActiveTintColor: '#f59e0b',
      tabBarInactiveTintColor: '#9ca3af',
      headerStyle: { backgroundColor: '#1f2937' },
      headerTintColor: '#fff',
    }}
  >
    <Tab.Screen name="OrgDashboard" component={OrgDashboardScreen} options={{ title: 'Dashboard Organisateur' }} />
    <Tab.Screen name="Créer Événement" component={OrgCreateEventScreen} options={{ title: 'Créer un Événement' }} />
    <Tab.Screen name="Profil" component={ProfileScreen} />
  </Tab.Navigator>
);

const AppNavigator = () => {
  const { token, user, isLoading } = useContext(AuthContext);

  if (isLoading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#111827' }}>
        <ActivityIndicator size="large" color="#10b981" />
      </View>
    );
  }

  return (
    <NavigationContainer>
      <Stack.Navigator screenOptions={{ headerShown: false }}>
        {token ? (
          // Navigation selon le rôle de l'utilisateur connecté
          user?.role === 'scanner' ? (
            <Stack.Screen name="ScannerHome" component={ScannerTabNavigator} />
          ) : user?.role === 'admin' ? (
            // L'admin a accès à l'interface organisateur complète
            <Stack.Screen name="OrganizerHome" component={OrganizerTabNavigator} />
          ) : (
            // Par défaut, l'utilisateur a accès aux fonctionnalités participant
            // Mais l'interface organisateur est accessible s'il le souhaite (ou s'il crée un événement)
            <Stack.Screen name="ParticipantHome" component={ParticipantTabNavigator} />
          )
        ) : (
          // Pas connecté - Écrans de connexion / inscription
          <>
            <Stack.Screen name="Login" component={LoginScreen} />
            <Stack.Screen name="Register" component={RegisterScreen} />
          </>
        )}
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default AppNavigator;
