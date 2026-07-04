import React, { useState, useContext } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView, ImageBackground } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';

export default function RegisterScreen({ navigation }) {
  const [nom, setNom] = useState('');
  const [prenom, setPrenom] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const { register, isLoading } = useContext(AuthContext);

  const handleRegister = async () => {
    if (!nom || !prenom || !email || !phone || !password || !passwordConfirmation) {
      Alert.alert('Champs requis', 'Veuillez remplir tous les champs du formulaire.');
      return;
    }

    if (password !== passwordConfirmation) {
      Alert.alert('Erreur', 'Les mots de passe ne correspondent pas.');
      return;
    }

    const result = await register({
      nom,
      prenom,
      email,
      phone,
      password,
      password_confirmation: passwordConfirmation,
    });

    if (!result.success) {
      if (result.errors) {
        const firstErrorKey = Object.keys(result.errors)[0];
        const firstErrorMessage = result.errors[firstErrorKey][0];
        Alert.alert('Erreur de validation', firstErrorMessage);
      } else {
        Alert.alert('Erreur', result.message);
      }
    } else {
      navigation.navigate('ParticipantHome');
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled" showsVerticalScrollIndicator={false}>
        {/* Header Image Background (Screen 3) */}
        <ImageBackground
          source={{ uri: 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=800' }}
          style={styles.headerBg}
          resizeMode="cover"
        >
          <View style={styles.headerOverlay}>
            {/* Back Button */}
            <TouchableOpacity style={styles.backButton} onPress={() => navigation.navigate('Login')}>
              <Ionicons name="chevron-back" size={22} color="#1b4332" />
            </TouchableOpacity>
          </View>
        </ImageBackground>

        {/* Curved Card Container */}
        <View style={styles.cardContainer}>
          <Text style={styles.welcomeTitle}>S'inscrire</Text>
          <Text style={styles.welcomeSubtitle}>Créez votre nouveau compte</Text>

          <View style={styles.form}>
            <Text style={styles.label}>Nom</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="person-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Dupont"
                placeholderTextColor="#7a8b7c"
                value={nom}
                onChangeText={setNom}
              />
            </View>

            <Text style={styles.label}>Prénom</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="person-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Jean"
                placeholderTextColor="#7a8b7c"
                value={prenom}
                onChangeText={setPrenom}
              />
            </View>

            <Text style={styles.label}>Téléphone</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="call-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="90000000"
                placeholderTextColor="#7a8b7c"
                keyboardType="phone-pad"
                value={phone}
                onChangeText={setPhone}
              />
            </View>

            <Text style={styles.label}>Adresse Email</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="mail-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="jean.dupont@email.com"
                placeholderTextColor="#7a8b7c"
                keyboardType="email-address"
                autoCapitalize="none"
                value={email}
                onChangeText={setEmail}
              />
            </View>

            <Text style={styles.label}>Mot de passe</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="lock-closed-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Au moins 8 caractères"
                placeholderTextColor="#7a8b7c"
                secureTextEntry={!showPassword}
                autoCapitalize="none"
                value={password}
                onChangeText={setPassword}
              />
              <TouchableOpacity onPress={() => setShowPassword(!showPassword)}>
                <Ionicons 
                  name={showPassword ? "eye-off-outline" : "eye-outline"} 
                  size={18} 
                  color="#7a8b7c" 
                />
              </TouchableOpacity>
            </View>

            <Text style={styles.label}>Confirmer le mot de passe</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="lock-closed-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Saisissez à nouveau le mot de passe"
                placeholderTextColor="#7a8b7c"
                secureTextEntry={!showPassword}
                autoCapitalize="none"
                value={passwordConfirmation}
                onChangeText={setPasswordConfirmation}
              />
            </View>

            <TouchableOpacity style={styles.button} onPress={handleRegister} disabled={isLoading}>
              {isLoading ? (
                <ActivityIndicator color="#fff" />
              ) : (
                <Text style={styles.buttonText} numberOfLines={1}>S'inscrire</Text>
              )}
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.loginLink}
              onPress={() => navigation.navigate('Login')}
            >
              <Text style={styles.loginText}>
                Vous avez déjà un compte ? <Text style={styles.loginAccent}>Se connecter</Text>
              </Text>
            </TouchableOpacity>
          </View>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#ffffff',
  },
  scrollContainer: {
    flexGrow: 1,
    backgroundColor: '#ffffff',
  },
  headerBg: {
    width: '100%',
    height: 180,
  },
  headerOverlay: {
    flex: 1,
    backgroundColor: 'rgba(15, 32, 24, 0.25)', // slight dark nature overlay
    paddingHorizontal: 20,
    paddingTop: Platform.OS === 'ios' ? 60 : 40,
  },
  backButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#f0f4f1',
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  cardContainer: {
    flex: 1,
    backgroundColor: '#ffffff',
    borderTopLeftRadius: 30,
    borderTopRightRadius: 30,
    marginTop: -40,
    paddingHorizontal: 28,
    paddingTop: 32,
    paddingBottom: 40,
  },
  welcomeTitle: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#1b4332',
    textAlign: 'left',
    marginBottom: 6,
  },
  welcomeSubtitle: {
    fontSize: 14,
    color: '#7a8b7c',
    textAlign: 'left',
    marginBottom: 24,
  },
  form: {
    width: '100%',
  },
  label: {
    fontSize: 13,
    color: '#5a6b5c',
    marginBottom: 8,
    fontWeight: '600',
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f0f4f1',
    borderRadius: 12,
    paddingHorizontal: 16,
    height: 50,
    marginBottom: 16,
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    color: '#1b4332',
    fontSize: 15,
    height: '100%',
  },
  button: {
    backgroundColor: '#2e6f40',
    borderRadius: 12,
    height: 52,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 10,
    width: '100%',
    shadowColor: '#2e6f40',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 8,
    elevation: 3,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
    textAlign: 'center',
    width: '100%',
  },
  loginLink: {
    alignItems: 'center',
    marginTop: 24,
  },
  loginText: {
    color: '#7a8b7c',
    fontSize: 14,
  },
  loginAccent: {
    color: '#2e6f40',
    fontWeight: 'bold',
  },
});
