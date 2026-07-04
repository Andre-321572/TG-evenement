import React, { useContext, useState } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, ActivityIndicator, Image, ScrollView, Switch, Alert, ImageBackground, Modal, TextInput } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';
import apiClient from '../../api/client';

export default function ProfileScreen({ navigation }) {
  const { user, logout, isLoading, token, updateUserProfile } = useContext(AuthContext);
  const [twoFactorEnabled, setTwoFactorEnabled] = useState(true);
  const [modalVisible, setModalVisible] = useState(false);
  const [editingField, setEditingField] = useState(''); // 'name', 'email', 'phone', 'password'
  const [editLabel, setEditLabel] = useState('');
  
  const [nomInput, setNomInput] = useState('');
  const [prenomInput, setPrenomInput] = useState('');
  const [emailInput, setEmailInput] = useState('');
  const [phoneInput, setPhoneInput] = useState('');
  const [passwordInput, setPasswordInput] = useState('');
  const [passwordConfirmInput, setPasswordConfirmInput] = useState('');
  const [isSaving, setIsSaving] = useState(false);
  const [selectedLanguage, setSelectedLanguage] = useState('Français (FR)');

  const handleLogout = async () => {
    await logout();
  };

  const handleEditName = () => {
    setNomInput(user?.nom || '');
    setPrenomInput(user?.prenom || '');
    setEditingField('name');
    setEditLabel('Modifier votre nom complet');
    setModalVisible(true);
  };

  const handleEditEmail = () => {
    setEmailInput(user?.email || '');
    setEditingField('email');
    setEditLabel('Modifier votre adresse email');
    setModalVisible(true);
  };

  const handleEditPhone = () => {
    setPhoneInput(user?.phone || '');
    setEditingField('phone');
    setEditLabel('Modifier votre numéro de téléphone');
    setModalVisible(true);
  };

  const handleEditPassword = () => {
    setPasswordInput('');
    setPasswordConfirmInput('');
    setEditingField('password');
    setEditLabel('Changer de mot de passe');
    setModalVisible(true);
  };

  const handleLanguageChange = () => {
    Alert.alert(
      'Langue',
      'Choisissez votre langue :',
      [
        { text: 'Français (FR)', onPress: () => setSelectedLanguage('Français (FR)') },
        { text: 'English (US)', onPress: () => setSelectedLanguage('English (US)') },
        { text: 'Annuler', style: 'cancel' }
      ]
    );
  };

  const handleSave = async () => {
    setIsSaving(true);
    try {
      const payload = {};
      if (editingField === 'name') {
        payload.nom = nomInput;
        payload.prenom = prenomInput;
      } else if (editingField === 'email') {
        payload.email = emailInput;
      } else if (editingField === 'phone') {
        payload.phone = phoneInput;
      } else if (editingField === 'password') {
        if (!passwordInput || passwordInput.length < 8) {
          Alert.alert('Erreur', 'Le mot de passe doit faire au moins 8 caractères.');
          setIsSaving(false);
          return;
        }
        if (passwordInput !== passwordConfirmInput) {
          Alert.alert('Erreur', 'Les mots de passe ne correspondent pas.');
          setIsSaving(false);
          return;
        }
        payload.password = passwordInput;
        payload.password_confirmation = passwordConfirmInput;
      }

      const response = await apiClient.post('/auth/update-profile', payload);
      if (response.data.status === 'success') {
        await updateUserProfile(response.data.user);
        Alert.alert('Succès', 'Profil mis à jour avec succès.');
        setModalVisible(false);
      } else {
        Alert.alert('Erreur', response.data.message || 'Impossible de mettre à jour le profil.');
      }
    } catch (e) {
      console.error(e);
      const errorMessage = e.response?.data?.message || 'Erreur lors de la mise à jour.';
      Alert.alert('Erreur', errorMessage);
    } finally {
      setIsSaving(false);
    }
  };

  // Profile section helper row
  const ProfileRow = ({ 
    icon, 
    label, 
    value, 
    onPress, 
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
                <Text style={styles.guestLoginBtnText} numberOfLines={1}>Se connecter</Text>
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
    <View style={{ flex: 1 }}>
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
          <ProfileRow icon="person-outline" label="Nom complet" value={`${user?.prenom} ${user?.nom}`} onPress={handleEditName} />
          <ProfileRow icon="mail-outline" label="Email" value={user?.email} onPress={handleEditEmail} />
          <ProfileRow icon="call-outline" label="Téléphone" value={user?.phone || 'Non renseigné'} onPress={handleEditPhone} />
        </View>

        {/* Sécurité */}
        <Text style={styles.sectionHeader}>Sécurité</Text>
        <View style={styles.sectionCard}>
          <ProfileRow icon="lock-closed-outline" label="Changer le mot de passe" onPress={handleEditPassword} />
          <ProfileRow 
            icon="shield-checkmark-outline" 
            label="Authentification à deux facteurs" 
            isSwitch={true} 
            switchValue={twoFactorEnabled} 
            onSwitchChange={(val) => { 
              setTwoFactorEnabled(val); 
              Alert.alert('Sécurité', val ? 'Authentification à deux facteurs activée !' : 'Authentification à deux facteurs désactivée !'); 
            }} 
          />
        </View>

        {/* Préférences */}
        <Text style={styles.sectionHeader}>Préférences</Text>
        <View style={styles.sectionCard}>
          <ProfileRow icon="notifications-outline" label="Notifications" onPress={() => navigation.navigate('Notifications')} />
          <ProfileRow icon="globe-outline" label="Langue" value={selectedLanguage} onPress={handleLanguageChange} />
        </View>

        {/* Autres */}
        <Text style={styles.sectionHeader}>Autres</Text>
        <View style={styles.sectionCard}>
          <ProfileRow icon="help-circle-outline" label="Aide & Support" onPress={() => Alert.alert('Aide & Support', 'Besoin d\'aide ? Contactez notre support par email à : contact@tgevent.digitalforges.org')} />
          <ProfileRow icon="document-text-outline" label="Conditions d'utilisation" onPress={() => Alert.alert('Conditions d\'utilisation', 'Les présentes conditions d\'utilisation régissent l\'accès et l\'achat de billets sur la plateforme TGevent. Veuillez utiliser des informations de paiement valides.')} />
          <ProfileRow icon="shield-outline" label="Politique de confidentialité" onPress={() => Alert.alert('Politique de confidentialité', 'Vos données de profil sont collectées uniquement pour l\'accès aux événements et l\'édition de vos billets. Elles ne sont jamais revendues ou partagées.')} />
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

      {/* Modal d'édition des infos du profil */}
      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalCard}>
            <Text style={styles.modalTitle}>{editLabel}</Text>
            
            {editingField === 'name' && (
              <>
                <TextInput
                  style={styles.modalInput}
                  placeholder="Prénom"
                  placeholderTextColor="#7a8b7c"
                  value={prenomInput}
                  onChangeText={setPrenomInput}
                />
                <TextInput
                  style={styles.modalInput}
                  placeholder="Nom"
                  placeholderTextColor="#7a8b7c"
                  value={nomInput}
                  onChangeText={setNomInput}
                />
              </>
            )}

            {editingField === 'email' && (
              <TextInput
                style={styles.modalInput}
                placeholder="Email"
                placeholderTextColor="#7a8b7c"
                keyboardType="email-address"
                autoCapitalize="none"
                value={emailInput}
                onChangeText={setEmailInput}
              />
            )}

            {editingField === 'phone' && (
              <TextInput
                style={styles.modalInput}
                placeholder="Téléphone"
                placeholderTextColor="#7a8b7c"
                keyboardType="phone-pad"
                value={phoneInput}
                onChangeText={setPhoneInput}
              />
            )}

            {editingField === 'password' && (
              <>
                <TextInput
                  style={styles.modalInput}
                  placeholder="Nouveau mot de passe (min 8 car.)"
                  placeholderTextColor="#7a8b7c"
                  secureTextEntry={true}
                  autoCapitalize="none"
                  value={passwordInput}
                  onChangeText={setPasswordInput}
                />
                <TextInput
                  style={styles.modalInput}
                  placeholder="Confirmer le mot de passe"
                  placeholderTextColor="#7a8b7c"
                  secureTextEntry={true}
                  autoCapitalize="none"
                  value={passwordConfirmInput}
                  onChangeText={setPasswordConfirmInput}
                />
              </>
            )}

            <View style={styles.modalActions}>
              <TouchableOpacity 
                style={[styles.modalBtn, styles.modalCancelBtn]} 
                onPress={() => setModalVisible(false)}
                disabled={isSaving}
              >
                <Text style={styles.modalCancelBtnText}>Annuler</Text>
              </TouchableOpacity>
              <TouchableOpacity 
                style={[styles.modalBtn, styles.modalSaveBtn]} 
                onPress={handleSave}
                disabled={isSaving}
              >
                {isSaving ? (
                  <ActivityIndicator color="#fff" size="small" />
                ) : (
                  <Text style={styles.modalSaveBtnText}>Enregistrer</Text>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
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
    marginTop: 28,
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#fecaca',
    borderRadius: 12,
    height: 52,
    justifyContent: 'center',
    alignItems: 'center',
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
  guestBackground: {
    flex: 1,
    width: '100%',
    height: '100%',
  },
  guestOverlay: {
    flex: 1,
    backgroundColor: 'rgba(15, 32, 24, 0.45)', // Botanical dark overlay
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
    textAlign: 'center',
    width: '100%',
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
  
  // Modal Edit Styles
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(15, 23, 42, 0.5)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  modalCard: {
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 24,
    width: '100%',
    maxWidth: 340,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 12,
    elevation: 5,
  },
  modalTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1b4332',
    marginBottom: 16,
    textAlign: 'center',
  },
  modalInput: {
    backgroundColor: '#f0f4f1',
    borderRadius: 8,
    height: 48,
    paddingHorizontal: 16,
    marginBottom: 12,
    color: '#1b4332',
    fontSize: 15,
  },
  modalActions: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 8,
  },
  modalBtn: {
    flex: 1,
    height: 44,
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    marginHorizontal: 6,
  },
  modalCancelBtn: {
    backgroundColor: '#f1f5f9',
    borderWidth: 1,
    borderColor: '#cbd5e1',
  },
  modalCancelBtnText: {
    color: '#475569',
    fontWeight: '600',
  },
  modalSaveBtn: {
    backgroundColor: '#2e6f40',
  },
  modalSaveBtnText: {
    color: '#fff',
    fontWeight: '600',
  },
});
