import React, { useState, useContext } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView, ImageBackground } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';

export default function LoginScreen({ navigation }) {
  const [loginInput, setLoginInput] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const { login, isLoading } = useContext(AuthContext);

  const handleLogin = async () => {
    if (!loginInput || !password) {
      Alert.alert('Champs requis', 'Veuillez saisir votre identifiant et votre mot de passe.');
      return;
    }

    const result = await login(loginInput, password);
    if (!result.success) {
      Alert.alert('Erreur de connexion', result.message);
    } else {
      if (navigation.canGoBack()) {
        navigation.goBack();
      } else {
        navigation.navigate('ParticipantHome');
      }
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled" showsVerticalScrollIndicator={false}>
        {/* Header Image Background (Screen 2) */}
        <ImageBackground
          source={{ uri: 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=800' }}
          style={styles.headerBg}
          resizeMode="cover"
        >
          <View style={styles.headerOverlay}>
            {/* Back Button */}
            {navigation.canGoBack() && (
              <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
                <Ionicons name="chevron-back" size={22} color="#1b4332" />
              </TouchableOpacity>
            )}
          </View>
        </ImageBackground>

        {/* Curved Card Container */}
        <View style={styles.cardContainer}>
          <Text style={styles.welcomeTitle}>Bon retour !</Text>
          <Text style={styles.welcomeSubtitle}>Connectez-vous à votre compte</Text>

          <View style={styles.form}>
            <Text style={styles.label}>Adresse Email ou Téléphone</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="mail-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="user@mail.com"
                placeholderTextColor="#7a8b7c"
                value={loginInput}
                onChangeText={setLoginInput}
                autoCapitalize="none"
                keyboardType="email-address"
              />
            </View>

            <Text style={styles.label}>Mot de passe</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="lock-closed-outline" size={18} color="#7a8b7c" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="••••••••"
                placeholderTextColor="#7a8b7c"
                secureTextEntry={!showPassword}
                value={password}
                onChangeText={setPassword}
                autoCapitalize="none"
              />
              <TouchableOpacity onPress={() => setShowPassword(!showPassword)}>
                <Ionicons 
                  name={showPassword ? "eye-off-outline" : "eye-outline"} 
                  size={18} 
                  color="#7a8b7c" 
                />
              </TouchableOpacity>
            </View>

            <TouchableOpacity style={styles.button} onPress={handleLogin} disabled={isLoading}>
              {isLoading ? (
                <ActivityIndicator color="#fff" />
              ) : (
                <Text style={styles.buttonText} numberOfLines={1}>Se connecter</Text>
              )}
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.registerLink}
              onPress={() => navigation.navigate('Register')}
            >
              <Text style={styles.registerText}>
                Vous n'avez pas de compte ? <Text style={styles.registerAccent}>S'inscrire</Text>
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
    height: 250,
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
    marginBottom: 28,
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
    height: 52,
    marginBottom: 20,
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
  registerLink: {
    alignItems: 'center',
    marginTop: 24,
  },
  registerText: {
    color: '#7a8b7c',
    fontSize: 14,
  },
  registerAccent: {
    color: '#2e6f40',
    fontWeight: 'bold',
  },
});
