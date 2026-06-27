import React, { useContext } from 'react';
import { View, ActivityIndicator } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../context/AuthContext';

// Import des écrans partagés
import LoginScreen from '../screens/shared/LoginScreen';
import RegisterScreen from '../screens/shared/RegisterScreen';
import ProfileScreen from '../screens/shared/ProfileScreen';
import NotificationsScreen from '../screens/shared/NotificationsScreen';

// Participant screens
import EventListScreen from '../screens/participant/EventListScreen';
import EventDetailScreen from '../screens/participant/EventDetailScreen';
import CheckoutScreen from '../screens/participant/CheckoutScreen';
import TicketListScreen from '../screens/participant/TicketListScreen';
import SearchScreen from '../screens/participant/SearchScreen';

// Scanner screens
import ScannerScreen from '../screens/scanner/ScannerScreen';
import ScanStatsScreen from '../screens/scanner/ScanStatsScreen';

// Organizer screens
import OrgDashboardScreen from '../screens/organizer/OrgDashboardScreen';
import OrgCreateEventScreen from '../screens/organizer/OrgCreateEventScreen';

const Stack = createStackNavigator();
const Tab = createBottomTabNavigator();

// Stack de navigation pour l'exploration des événements (Participant)
const ExploreStack = () => (
  <Stack.Navigator 
    screenOptions={{ 
      headerStyle: { backgroundColor: '#ffffff', borderBottomWidth: 1, borderBottomColor: '#f1f5f9', elevation: 0, shadowOpacity: 0 }, 
      headerTintColor: '#0f172a', 
      headerTitleStyle: { fontWeight: 'bold' } 
    }}
  >
    <Stack.Screen name="EventList" component={EventListScreen} options={{ headerShown: false }} />
    <Stack.Screen name="EventDetail" component={EventDetailScreen} options={{ title: 'Détails' }} />
    <Stack.Screen name="Checkout" component={CheckoutScreen} options={{ title: 'Paiement Sécurisé' }} />
  </Stack.Navigator>
);

// Navigation Participant (Tab avec 5 onglets)
const ParticipantTabNavigator = () => (
  <Tab.Navigator
    screenOptions={({ route }) => ({
      headerShown: false,
      tabBarStyle: { 
        backgroundColor: '#ffffff', 
        borderTopWidth: 1, 
        borderTopColor: '#e2e8f0',
        height: 60,
        paddingBottom: 8,
        paddingTop: 8,
      },
      tabBarActiveTintColor: '#1e3a8a',
      tabBarInactiveTintColor: '#94a3b8',
      tabBarLabelStyle: {
        fontSize: 11,
        fontWeight: '600',
      },
      tabBarIcon: ({ focused, color, size }) => {
        let iconName;
        if (route.name === 'Découvrir') {
          iconName = focused ? 'compass' : 'compass-outline';
        } else if (route.name === 'Recherche') {
          iconName = focused ? 'search' : 'search-outline';
        } else if (route.name === 'Billets') {
          iconName = focused ? 'ticket' : 'ticket-outline';
        } else if (route.name === 'Notifications') {
          iconName = focused ? 'notifications' : 'notifications-outline';
        } else if (route.name === 'Profil') {
          iconName = focused ? 'person' : 'person-outline';
        }
        return <Ionicons name={iconName} size={22} color={color} />;
      },
    })}
  >
    <Tab.Screen name="Découvrir" component={ExploreStack} />
    <Tab.Screen name="Recherche" component={SearchScreen} />
    <Tab.Screen name="Billets" component={TicketListScreen} options={{ headerShown: true, headerTitle: 'Mes Billets', headerStyle: { backgroundColor: '#ffffff' }, headerTintColor: '#1e3a8a', headerTitleStyle: { fontWeight: 'bold' } }} />
    <Tab.Screen name="Notifications" component={NotificationsScreen} />
    <Tab.Screen name="Profil" component={ProfileScreen} />
  </Tab.Navigator>
);

// Navigation Scanner (Tab)
const ScannerTabNavigator = () => (
  <Tab.Navigator
    screenOptions={({ route }) => ({
      tabBarStyle: { backgroundColor: '#ffffff', borderTopWidth: 1, borderTopColor: '#e2e8f0', height: 60, paddingBottom: 8, paddingTop: 8 },
      tabBarActiveTintColor: '#2563eb',
      tabBarInactiveTintColor: '#94a3b8',
      headerStyle: { backgroundColor: '#ffffff', borderBottomWidth: 1, borderBottomColor: '#f1f5f9' },
      headerTintColor: '#1e3a8a',
      headerTitleStyle: { fontWeight: 'bold' },
      tabBarIcon: ({ focused, color, size }) => {
        let iconName;
        if (route.name === 'Scanner Billet') {
          iconName = focused ? 'qr-code' : 'qr-code-outline';
        } else if (route.name === 'Statistiques') {
          iconName = focused ? 'stats-chart' : 'stats-chart-outline';
        } else if (route.name === 'Profil') {
          iconName = focused ? 'person' : 'person-outline';
        }
        return <Ionicons name={iconName} size={22} color={color} />;
      },
    })}
  >
    <Tab.Screen name="Scanner Billet" component={ScannerScreen} options={{ title: 'Scan Caméra' }} />
    <Tab.Screen name="Statistiques" component={ScanStatsScreen} options={{ title: 'Stats de Scan' }} />
    <Tab.Screen name="Profil" component={ProfileScreen} />
  </Tab.Navigator>
);

// Navigation Organisateur (Tab)
const OrganizerTabNavigator = () => (
  <Tab.Navigator
    screenOptions={({ route }) => ({
      tabBarStyle: { backgroundColor: '#ffffff', borderTopWidth: 1, borderTopColor: '#e2e8f0', height: 60, paddingBottom: 8, paddingTop: 8 },
      tabBarActiveTintColor: '#f59e0b',
      tabBarInactiveTintColor: '#94a3b8',
      headerStyle: { backgroundColor: '#ffffff', borderBottomWidth: 1, borderBottomColor: '#f1f5f9' },
      headerTintColor: '#1e3a8a',
      headerTitleStyle: { fontWeight: 'bold' },
      tabBarIcon: ({ focused, color, size }) => {
        let iconName;
        if (route.name === 'OrgDashboard') {
          iconName = focused ? 'grid' : 'grid-outline';
        } else if (route.name === 'Créer Événement') {
          iconName = focused ? 'add-circle' : 'add-circle-outline';
        } else if (route.name === 'Profil') {
          iconName = focused ? 'person' : 'person-outline';
        }
        return <Ionicons name={iconName} size={22} color={color} />;
      },
    })}
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
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#f8fafc' }}>
        <ActivityIndicator size="large" color="#2563eb" />
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
            <Stack.Screen name="OrganizerHome" component={OrganizerTabNavigator} />
          ) : (
            <Stack.Screen name="ParticipantHome" component={ParticipantTabNavigator} />
          )
        ) : (
          // Par défaut, accès libre au navigateur participant pour la consultation des événements
          <Stack.Screen name="ParticipantHome" component={ParticipantTabNavigator} />
        )}
        
        {/* Écrans de connexion / inscription accessibles en pile (modals) */}
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Register" component={RegisterScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default AppNavigator;
