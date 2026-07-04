import React, { useContext } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, ActivityIndicator, Image, ScrollView, Switch, Alert, ImageBackground } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';

export default function ProfileScreen({ navigation }) {
  const { user, logout, isLoading, token } = useContext(AuthContext);

  const handleLogout = async () => {
    await logout();
  };

  // Profile section helper row
  const ProfileRow = ({ 
    icon, 
    label, 
    value, 
    onPress = () => Alert.alert('Profil', `La modification de "${label}" sera bientôt disponible !`), 
    hasChevron = true, 
    isSwitch = false, 
    switchValue = false, 
    onSwitchChange = null 
  }) => (
    <TouchableOpacity 
      style={styles.row} 
      onPress={onPress}
      disabled={isSwitch}
    >
      <View style={styles.rowLeft}>
        <Ionicons name={icon} size={20} color="#64748b" style={styles.rowIcon} />
        <View>
          <Text style={styles.rowLabel}>{label}</Text>
          {value && <Text style={styles.rowValue}>{value}</Text>}
        </View>
      </View>
      {isSwitch ? (
        <Switch 
          value={switchValue} 
          onValueChange={onSwitchChange}
          trackColor={{ false: '#cbd5e1', true: '#93c5fd' }}
          thumbColor={switchValue ? '#2563eb' : '#f4f4f5'}
        />
      ) : (
        hasChevron && <Ionicons name="chevron-forward" size={16} color="#94a3b8" />
      )}
    </TouchableOpacity>
  );

  // État invité (non connecté) - Écran 1
  if (!token) {
    return (
      <ImageBackground 
        source={{ uri: 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=800' }} 
        style={styles.guestBackground}
        resizeMode="cover"
      >
        <View style={styles.guestOverlay}>
          <View style={styles.guestContentContainer}>
            <Text style={styles.guestWelcomeTitle}>
              La meilleure{"\n"}application pour{"\n"}vos événements
            </Text>
            
            <View style={styles.guestActionsContainer}>
              <TouchableOpacity 
                style={styles.guestLoginBtn}
                onPress={() => navigation.navigate('Login')}
              >
                <Text style={styles.guestLoginBtnText}>Se connecter</Text>
              </TouchableOpacity>
              
              <TouchableOpacity 
                style={styles.guestRegisterLink}
                onPress={() => navigation.navigate('Register')}
              >
                <Text style={styles.guestRegisterLinkText}>Créer un compte</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </ImageBackground>
    );
  }

  // Utilisateur connecté
  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>
      {/* Profile Header Card */}
      <View style={styles.profileHeaderCard}>
        <View style={styles.avatarContainer}>
          {user?.img_profil ? (
            <Image source={{ uri: user.img_profil }} style={styles.avatarImage} />
          ) : (
            <View style={styles.avatarFallback}>
              <Text style={styles.avatarFallbackText}>
                {user?.prenom?.charAt(0).toUpperCase()}
                {user?.nom?.charAt(0).toUpperCase()}
              </Text>
            </View>
          )}
          <TouchableOpacity 
            style={styles.editAvatarButton}
            onPress={() => Alert.alert('Photo de profil', 'La modification de la photo de profil sera bientôt disponible !')}
          >
            <Ionicons name="pencil" size={12} color="#fff" />
          </TouchableOpacity>
        </View>

        <Text style={styles.userName}>{user?.prenom} {user?.nom}</Text>
        <Text style={styles.userEmail}>{user?.email}</Text>

        <View style={styles.badgeRow}>
          <View style={[styles.badge, styles.badgeBlue]}>
            <Text style={styles.badgeTextBlue}>Membre Premium</Text>
          </View>
          <View style={[styles.badge, styles.badgeRed]}>
            <Text style={styles.badgeTextRed}>12 Événements</Text>
          </View>
        </View>
      </View>

      {/* Informations Personnelles */}
      <Text style={styles.sectionHeader}>Informations Personnelles</Text>
      <View style={styles.sectionCard}>
        <ProfileRow icon="person-outline" label="Nom complet" value={`${user?.prenom} ${user?.nom}`} hasChevron={true} />
        <ProfileRow icon="mail-outline" label="Email" value={user?.email} hasChevron={true} />
        <ProfileRow icon="call-outline" label="Téléphone" value={user?.phone || '+228 90 00 00 00'} hasChevron={true} />
      </View>

      {/* Sécurité */}
      <Text style={styles.sectionHeader}>Sécurité</Text>
      <View style={styles.sectionCard}>
        <ProfileRow icon="lock-closed-outline" label="Changer le mot de passe" hasChevron={true} />
        <ProfileRow icon="shield-checkmark-outline" label="Authentification à deux facteurs" isSwitch={true} switchValue={true} />
      </View>

      {/* Préférences */}
      <Text style={styles.sectionHeader}>Préférences</Text>
      <View style={styles.sectionCard}>
        <ProfileRow icon="notifications-outline" label="Notifications" hasChevron={true} />
        <ProfileRow icon="globe-outline" label="Langue" value="Français (FR)" hasChevron={true} />
      </View>

      {/* Autres */}
      <Text style={styles.sectionHeader}>Autres</Text>
      <View style={styles.sectionCard}>
        <ProfileRow icon="help-circle-outline" label="Aide & Support" hasChevron={true} />
        <ProfileRow icon="document-text-outline" label="Conditions d'utilisation" hasChevron={true} />
        <ProfileRow icon="shield-outline" label="Politique de confidentialité" hasChevron={true} />
      </View>

      {/* Déconnexion Button */}
      <TouchableOpacity style={styles.logoutButton} onPress={handleLogout} disabled={isLoading}>
        {isLoading ? (
          <ActivityIndicator color="#ef4444" />
        ) : (
          <View style={styles.logoutContent}>
            <Ionicons name="log-out-outline" size={20} color="#ef4444" style={{ marginRight: 8 }} />
            <Text style={styles.logoutText}>Déconnexion</Text>
          </View>
        )}
      </TouchableOpacity>

      <Text style={styles.versionText}>Version 1.0.0 (Build 120)</Text>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  scrollContent: {
    padding: 20,
    paddingBottom: 40,
  },
  profileHeaderCard: {
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 24,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.02,
    shadowRadius: 8,
    elevation: 2,
    marginBottom: 20,
  },
  avatarContainer: {
    position: 'relative',
    marginBottom: 16,
  },
  avatarImage: {
    width: 90,
    height: 90,
    borderRadius: 45,
  },
  avatarFallback: {
    width: 90,
    height: 90,
    borderRadius: 45,
    backgroundColor: '#2563eb',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarFallbackText: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
  },
  editAvatarButton: {
    position: 'absolute',
    bottom: 0,
    right: 0,
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: '#1e3a8a',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#fff',
  },
  userName: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 4,
  },
  userEmail: {
    fontSize: 13,
    color: '#64748b',
    marginBottom: 16,
  },
  badgeRow: {
    flexDirection: 'row',
  },
  badge: {
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 99,
    marginHorizontal: 4,
  },
  badgeBlue: {
    backgroundColor: '#eff6ff',
  },
  badgeRed: {
    backgroundColor: '#ffe4e6',
  },
  badgeTextBlue: {
    color: '#2563eb',
    fontSize: 11,
    fontWeight: 'bold',
  },
  badgeTextRed: {
    color: '#e11d48',
    fontSize: 11,
    fontWeight: 'bold',
  },
  sectionHeader: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#1e3a8a',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginTop: 16,
    marginBottom: 8,
    marginLeft: 4,
  },
  sectionCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    overflow: 'hidden',
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
  },
  rowLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  rowIcon: {
    marginRight: 12,
  },
  rowLabel: {
    fontSize: 14,
    fontWeight: '600',
    color: '#0f172a',
  },
  rowValue: {
    fontSize: 12,
    color: '#64748b',
    marginTop: 2,
  },
  logoutButton: {
    backgroundColor: '#fef2f2',
    borderRadius: 12,
    paddingVertical: 14,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 24,
    borderWidth: 1,
    borderColor: '#fee2e2',
  },
  logoutContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logoutText: {
    color: '#ef4444',
    fontSize: 16,
    fontWeight: 'bold',
  },
  versionText: {
    textAlign: 'center',
    color: '#94a3b8',
    fontSize: 11,
    marginTop: 24,
  },
  guestContainer: {
    flex: 1,
    backgroundColor: '#f8fafc',
    justifyContent: 'center',
    padding: 24,
  },
  guestCard: {
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 32,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 4,
  },
  guestIconBg: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#eff6ff',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
  },
  guestBackground: {
    flex: 1,
    width: '100%',
    height: '100%',
  },
  guestOverlay: {
    flex: 1,
    backgroundColor: 'rgba(15, 32, 24, 0.45)', // Botanical dark tint overlay
    justifyContent: 'space-between',
    padding: 32,
    paddingTop: 80,
    paddingBottom: 60,
  },
  guestContentContainer: {
    flex: 1,
    justifyContent: 'space-between',
  },
  guestWelcomeTitle: {
    fontSize: 36,
    fontWeight: 'bold',
    color: '#ffffff',
    lineHeight: 46,
    marginTop: 20,
    textShadowColor: 'rgba(0, 0, 0, 0.25)',
    textShadowOffset: { width: 0, height: 2 },
    textShadowRadius: 4,
  },
  guestActionsContainer: {
    width: '100%',
    alignItems: 'center',
  },
  guestLoginBtn: {
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
    borderWidth: 1.5,
    borderColor: '#ffffff',
    borderRadius: 28,
    height: 56,
    width: '100%',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  guestLoginBtnText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: 'bold',
    letterSpacing: 0.5,
  },
  guestRegisterLink: {
    paddingVertical: 10,
  },
  guestRegisterLinkText: {
    color: 'rgba(255, 255, 255, 0.95)',
    fontSize: 15,
    fontWeight: '600',
    textDecorationLine: 'underline',
  },
});
