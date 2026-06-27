import React, { useState, useContext } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView } from 'react-native';
import { AuthContext } from '../../context/AuthContext';

export default function LoginScreen({ navigation }) {
  const [loginInput, setLoginInput] = useState('');
  const [password, setPassword] = useState('');
  const { login, isLoading } = useContext(AuthContext);

  const handleLogin = async () => {
    if (!loginInput || !password) {
      Alert.alert('Champs requis', 'Veuillez saisir votre identifiant et votre mot de passe.');
      return;
    }

    const result = await login(loginInput, password);
    if (!result.success) {
      Alert.alert('Erreur de connexion', result.message);
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled">
        <View style={styles.header}>
          <Text style={styles.logo}>TGevent</Text>
          <Text style={styles.subtitle}>Gérez vos événements et billets en toute simplicité</Text>
        </View>

        <View style={styles.form}>
          <Text style={styles.label}>Adresse Email ou Téléphone</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: jean.dupont@email.com"
            placeholderTextColor="#6b7280"
            value={loginInput}
            onChangeText={setLoginInput}
            autoCapitalize="none"
            keyboardType="email-address"
          />

          <Text style={styles.label}>Mot de passe</Text>
          <TextInput
            style={styles.input}
            placeholder="Saisissez votre mot de passe"
            placeholderTextColor="#6b7280"
            secureTextEntry
            value={password}
            onChangeText={setPassword}
            autoCapitalize="none"
          />

          <TouchableOpacity style={styles.button} onPress={handleLogin} disabled={isLoading}>
            {isLoading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.buttonText}>Se connecter</Text>
            )}
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.registerLink}
            onPress={() => navigation.navigate('Register')}
          >
            <Text style={styles.registerText}>
              Vous n'avez pas de compte ? <Text style={styles.registerAccent}>Créer un compte</Text>
            </Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#111827',
  },
  scrollContainer: {
    flexGrow: 1,
    justifyContent: 'center',
    padding: 24,
  },
  header: {
    alignItems: 'center',
    marginBottom: 40,
  },
  logo: {
    fontSize: 36,
    fontWeight: 'bold',
    color: '#10b981',
    letterSpacing: 1.5,
  },
  subtitle: {
    fontSize: 14,
    color: '#9ca3af',
    textAlign: 'center',
    marginTop: 8,
    lineHeight: 20,
  },
  form: {
    backgroundColor: '#1f2937',
    borderRadius: 16,
    padding: 24,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 8,
  },
  label: {
    fontSize: 14,
    color: '#d1d5db',
    marginBottom: 8,
    fontWeight: '600',
  },
  input: {
    backgroundColor: '#374151',
    color: '#fff',
    borderRadius: 8,
    paddingHorizontal: 16,
    paddingVertical: 12,
    fontSize: 16,
    marginBottom: 20,
    borderWidth: 1,
    borderColor: '#4b5563',
  },
  button: {
    backgroundColor: '#10b981',
    borderRadius: 8,
    paddingVertical: 14,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 10,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  registerLink: {
    alignItems: 'center',
    marginTop: 20,
  },
  registerText: {
    color: '#9ca3af',
    fontSize: 14,
  },
  registerAccent: {
    color: '#10b981',
    fontWeight: 'bold',
  },
});
