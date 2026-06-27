import React, { useContext, useState } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, ActivityIndicator, Image, ScrollView, Platform, Switch } from 'react-native';
import { Ionicons, Feather, MaterialIcons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';

export default function ProfileScreen({ navigation }) {
  const { user, logout, isLoading } = useContext(AuthContext);
  const [twoFactorEnabled, setTwoFactorEnabled] = useState(true);

  // Valeurs par défaut si non définies chez l'utilisateur
  const userPrenom = user?.prenom || 'Alexandre';
  const userNom = user?.nom || 'Dubois';
  const userEmail = user?.email || 'alexandre.dubois@email.com';
  const userPhone = user?.phone || '+33 6 12 34 56 78';

  const renderSectionHeader = (title) => (
    <Text style={styles.sectionHeaderTitle}>{title}</Text>
  );

  const renderRow = (icon, label, value, showChevron = true, onPress = null) => (
    <TouchableOpacity
      style={styles.row}
      onPress={onPress}
      disabled={!onPress}
      activeOpacity={0.7}
    >
      <View style={styles.rowLeft}>
        <Ionicons name={icon} size={20} color="#64748b" style={styles.rowIcon} />
        <View style={styles.rowTexts}>
          <Text style={styles.rowLabel}>{label}</Text>
          {value ? <Text style={styles.rowValue}>{value}</Text> : null}
        </View>
      </View>
      {showChevron && <Feather name="chevron-right" size={16} color="#94a3b8" />}
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
        <TouchableOpacity style={styles.iconButton}>
          <Feather name="settings" size={20} color="#1e293b" />
        </TouchableOpacity>
      </View>

      <ScrollView contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>
        {/* AVATAR & CARD SECTION */}
        <View style={styles.profileCard}>
          <View style={styles.avatarWrapper}>
            {user?.img_profil ? (
              <Image source={{ uri: user.img_profil }} style={styles.avatarImage} />
            ) : (
              <View style={styles.avatarFallback}>
                <Text style={styles.avatarFallbackText}>
                  {userPrenom.charAt(0).toUpperCase()}
                  {userNom.charAt(0).toUpperCase()}
                </Text>
              </View>
            )}
            <TouchableOpacity style={styles.editAvatarBtn} activeOpacity={0.8}>
              <Feather name="edit-2" size={12} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <Text style={styles.profileName}>{userPrenom} {userNom}</Text>
          <Text style={styles.profileEmail}>{userEmail}</Text>

          {/* BADGES */}
          <View style={styles.badgesRow}>
            <View style={styles.badgePremium}>
              <Text style={styles.badgePremiumText}>
                {user?.role === 'admin' ? 'Administrateur' : user?.role === 'scanner' ? 'Scanner Pro' : 'Membre Premium'}
              </Text>
            </View>
            <View style={styles.badgeEvents}>
              <Text style={styles.badgeEventsText}>12 Événements</Text>
            </View>
          </View>
        </View>

        {/* SECTION : INFORMATIONS PERSONNELLES */}
        <View style={styles.section}>
          {renderSectionHeader('Informations Personnelles')}
          <View style={styles.sectionBody}>
            {renderRow('person-outline', 'Nom complet', `${userPrenom} ${userNom}`)}
            {renderRow('mail-outline', 'Email', userEmail)}
            {renderRow('phone-portrait-outline', 'Téléphone', userPhone)}
          </View>
        </View>

        {/* SECTION : SÉCURITÉ */}
        <View style={styles.section}>
          {renderSectionHeader('Sécurité')}
          <View style={styles.sectionBody}>
            {renderRow('lock-closed-outline', 'Changer le mot de passe')}
            
            {/* Custom 2FA row with switch */}
            <View style={styles.row}>
              <View style={styles.rowLeft}>
                <Ionicons name="shield-checkmark-outline" size={20} color="#64748b" style={styles.rowIcon} />
                <View style={styles.rowTexts}>
                  <Text style={styles.rowLabel}>Authentification à deux facteurs</Text>
                </View>
              </View>
              <Switch
                value={twoFactorEnabled}
                onValueChange={setTwoFactorEnabled}
                trackColor={{ false: '#e2e8f0', true: '#1e3a8a' }}
                thumbColor={Platform.OS === 'android' ? '#ffffff' : undefined}
              />
            </View>
          </View>
        </View>

        {/* SECTION : PRÉFÉRENCES */}
        <View style={styles.section}>
          {renderSectionHeader('Préférences')}
          <View style={styles.sectionBody}>
            {renderRow('notifications-outline', 'Notifications')}
            {renderRow('globe-outline', 'Langue', 'Français (FR)')}
          </View>
        </View>

        {/* SECTION : AUTRES */}
        <View style={styles.section}>
          {renderSectionHeader('Autres')}
          <View style={styles.sectionBody}>
            {renderRow('help-circle-outline', 'Aide & Support')}
            {renderRow('document-text-outline', 'Conditions d\'utilisation')}
            {renderRow('lock-open-outline', 'Politique de confidentialité')}
          </View>
        </View>

        {/* LOGOUT BUTTON */}
        <TouchableOpacity
          style={styles.logoutBtn}
          onPress={logout}
          disabled={isLoading}
          activeOpacity={0.8}
        >
          {isLoading ? (
            <ActivityIndicator color="#ef4444" />
          ) : (
            <>
              <Feather name="log-out" size={18} color="#ef4444" style={{ marginRight: 8 }} />
              <Text style={styles.logoutBtnText}>Déconnexion</Text>
            </>
          )}
        </TouchableOpacity>

        {/* VERSION */}
        <Text style={styles.versionText}>Version 2.4.0 (Build 882)</Text>
      </ScrollView>
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
  scrollContent: {
    paddingBottom: 40,
  },
  profileCard: {
    alignItems: 'center',
    backgroundColor: '#ffffff',
    paddingVertical: 24,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
    marginBottom: 16,
  },
  avatarWrapper: {
    position: 'relative',
    marginBottom: 12,
  },
  avatarImage: {
    width: 90,
    height: 90,
    borderRadius: 45,
    borderWidth: 2,
    borderColor: '#e2e8f0',
  },
  avatarFallback: {
    width: 90,
    height: 90,
    borderRadius: 45,
    backgroundColor: '#e2e8f0',
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 2,
    borderColor: '#e2e8f0',
  },
  avatarFallbackText: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#475569',
  },
  editAvatarBtn: {
    position: 'absolute',
    bottom: 2,
    right: 2,
    width: 24,
    height: 24,
    borderRadius: 12,
    backgroundColor: '#1e3a8a',
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 2,
    borderColor: '#ffffff',
  },
  profileName: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#0f172a',
    marginBottom: 4,
  },
  profileEmail: {
    fontSize: 13,
    color: '#64748b',
    marginBottom: 14,
  },
  badgesRow: {
    flexDirection: 'row',
    gap: 8,
  },
  badgePremium: {
    backgroundColor: '#e0e7ff',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
  badgePremiumText: {
    color: '#1d4ed8',
    fontSize: 12,
    fontWeight: 'bold',
  },
  badgeEvents: {
    backgroundColor: '#fee2e2',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
  badgeEventsText: {
    color: '#ef4444',
    fontSize: 12,
    fontWeight: 'bold',
  },
  section: {
    marginHorizontal: 20,
    marginBottom: 20,
  },
  sectionHeaderTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 8,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  sectionBody: {
    backgroundColor: '#ffffff',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#f1f5f9',
    overflow: 'hidden',
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
    backgroundColor: '#ffffff',
  },
  rowLeft: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  rowIcon: {
    marginRight: 12,
  },
  rowTexts: {
    flex: 1,
  },
  rowLabel: {
    fontSize: 14,
    color: '#1e293b',
    fontWeight: '500',
  },
  rowValue: {
    fontSize: 12,
    color: '#64748b',
    marginTop: 2,
  },
  logoutBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#fee2e2',
    marginHorizontal: 20,
    marginTop: 10,
    marginBottom: 20,
    height: 48,
    borderRadius: 10,
  },
  logoutBtnText: {
    color: '#ef4444',
    fontSize: 15,
    fontWeight: 'bold',
  },
  versionText: {
    textAlign: 'center',
    color: '#94a3b8',
    fontSize: 12,
  },
});
