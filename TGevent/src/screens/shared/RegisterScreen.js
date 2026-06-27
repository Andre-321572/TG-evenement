import React, { useState, useContext } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView } from 'react-native';
import { AuthContext } from '../../context/AuthContext';

export default function RegisterScreen({ navigation }) {
  const [nom, setNom] = useState('');
  const [prenom, setPrenom] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
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
        // Afficher la première erreur de validation
        const firstErrorKey = Object.keys(result.errors)[0];
        const firstErrorMessage = result.errors[firstErrorKey][0];
        Alert.alert('Erreur de validation', firstErrorMessage);
      } else {
        Alert.alert('Erreur', result.message);
      }
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled">
        <View style={styles.header}>
          <Text style={styles.title}>Créer un compte</Text>
          <Text style={styles.subtitle}>Rejoignez TGevent et réservez vos billets en quelques clics</Text>
        </View>

        <View style={styles.form}>
          <Text style={styles.label}>Nom</Text>
          <TextInput
            style={styles.input}
            placeholder="Dupont"
            placeholderTextColor="#6b7280"
            value={nom}
            onChangeText={setNom}
          />

          <Text style={styles.label}>Prénom</Text>
          <TextInput
            style={styles.input}
            placeholder="Jean"
            placeholderTextColor="#6b7280"
            value={prenom}
            onChangeText={setPrenom}
          />

          <Text style={styles.label}>Téléphone</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: 90000000"
            placeholderTextColor="#6b7280"
            keyboardType="phone-pad"
            value={phone}
            onChangeText={setPhone}
          />

          <Text style={styles.label}>Adresse Email</Text>
          <TextInput
            style={styles.input}
            placeholder="jean.dupont@email.com"
            placeholderTextColor="#6b7280"
            keyboardType="email-address"
            autoCapitalize="none"
            value={email}
            onChangeText={setEmail}
          />

          <Text style={styles.label}>Mot de passe</Text>
          <TextInput
            style={styles.input}
            placeholder="Au moins 8 caractères"
            placeholderTextColor="#6b7280"
            secureTextEntry
            autoCapitalize="none"
            value={password}
            onChangeText={setPassword}
          />

          <Text style={styles.label}>Confirmer le mot de passe</Text>
          <TextInput
            style={styles.input}
            placeholder="Saisissez à nouveau"
            placeholderTextColor="#6b7280"
            secureTextEntry
            autoCapitalize="none"
            value={passwordConfirmation}
            onChangeText={setPasswordConfirmation}
          />

          <TouchableOpacity style={styles.button} onPress={handleRegister} disabled={isLoading}>
            {isLoading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.buttonText}>S'inscrire</Text>
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
    padding: 24,
    justifyContent: 'center',
  },
  header: {
    alignItems: 'center',
    marginBottom: 24,
    marginTop: 40,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
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
    marginBottom: 40,
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
    marginBottom: 16,
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
  loginLink: {
    alignItems: 'center',
    marginTop: 20,
  },
  loginText: {
    color: '#9ca3af',
    fontSize: 14,
  },
  loginAccent: {
    color: '#10b981',
    fontWeight: 'bold',
  },
});
