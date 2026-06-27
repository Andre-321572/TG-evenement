import React, { useState } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, ActivityIndicator, Alert, ScrollView, KeyboardAvoidingView, Platform } from 'react-native';
import apiClient from '../../api/client';

export default function OrgCreateEventScreen({ navigation }) {
  const [titre, setTitre] = useState('');
  const [categorie, setCategorie] = useState('');
  const [date, setDate] = useState('');
  const [startHeure, setStartHeure] = useState('');
  const [endHeure, setEndHeure] = useState('');
  const [lieu, setLieu] = useState('');
  const [description, setDescription] = useState('');
  const [nomProprietaire, setNomProprietaire] = useState('');
  const [telephone, setTelephone] = useState('');
  const [email, setEmail] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async () => {
    if (!titre || !categorie || !date || !startHeure || !endHeure || !lieu || !nomProprietaire) {
      Alert.alert('Champs requis', 'Veuillez remplir tous les champs obligatoires (*).');
      return;
    }

    setIsLoading(true);
    try {
      const response = await apiClient.post('/organisateur/events', {
        titre,
        categorie,
        date,
        start_heure: startHeure,
        end_heure: endHeure,
        lieu,
        description,
        nom_proprietaire: nomProprietaire,
        telephone,
        email,
      });

      if (response.data.status === 'success') {
        Alert.alert('Succès', 'Événement créé avec succès sous forme de brouillon !');
        // Reset form
        setTitre('');
        setCategorie('');
        setDate('');
        setStartHeure('');
        setEndHeure('');
        setLieu('');
        setDescription('');
        setNomProprietaire('');
        setTelephone('');
        setEmail('');
        // Retour au dashboard
        navigation.navigate('OrgDashboard');
      }
    } catch (error) {
      console.error(error);
      Alert.alert('Erreur', error.response?.data?.message || 'Erreur lors de la création de l\'événement.');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled" showsVerticalScrollIndicator={false}>
        <View style={styles.form}>
          <Text style={styles.sectionTitle}>Informations Générales</Text>

          <Text style={styles.label}>Titre de l'événement *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Concert Caritatif"
            placeholderTextColor="#94a3b8"
            value={titre}
            onChangeText={setTitre}
          />

          <Text style={styles.label}>Catégorie *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Concert, Conférence, Voyage..."
            placeholderTextColor="#94a3b8"
            value={categorie}
            onChangeText={setCategorie}
          />

          <Text style={styles.label}>Date *</Text>
          <TextInput
            style={styles.input}
            placeholder="Format: AAAA-MM-JJ (Ex: 2026-08-15)"
            placeholderTextColor="#94a3b8"
            value={date}
            onChangeText={setDate}
          />

          <View style={styles.timeRow}>
            <View style={{ width: '48%' }}>
              <Text style={styles.label}>Heure Début *</Text>
              <TextInput
                style={styles.input}
                placeholder="Ex: 19:00"
                placeholderTextColor="#94a3b8"
                value={startHeure}
                onChangeText={setStartHeure}
              />
            </View>
            <View style={{ width: '48%' }}>
              <Text style={styles.label}>Heure Fin *</Text>
              <TextInput
                style={styles.input}
                placeholder="Ex: 22:30"
                placeholderTextColor="#94a3b8"
                value={endHeure}
                onChangeText={setEndHeure}
              />
            </View>
          </View>

          <Text style={styles.label}>Lieu *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Grand Palais, Lomé"
            placeholderTextColor="#94a3b8"
            value={lieu}
            onChangeText={setLieu}
          />

          <Text style={styles.label}>Description</Text>
          <TextInput
            style={[styles.input, styles.textArea]}
            placeholder="Décrivez l'événement..."
            placeholderTextColor="#94a3b8"
            multiline
            numberOfLines={4}
            value={description}
            onChangeText={setDescription}
          />

          <Text style={styles.sectionTitle}>Contact & Organisateur</Text>

          <Text style={styles.label}>Nom de l'organisateur / propriétaire *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Association X"
            placeholderTextColor="#94a3b8"
            value={nomProprietaire}
            onChangeText={setNomProprietaire}
          />

          <Text style={styles.label}>Téléphone de contact</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: 90102030"
            placeholderTextColor="#94a3b8"
            keyboardType="phone-pad"
            value={telephone}
            onChangeText={setTelephone}
          />

          <Text style={styles.label}>Email de contact</Text>
          <TextInput
            style={styles.input}
            placeholder="contact@evenement.com"
            placeholderTextColor="#94a3b8"
            keyboardType="email-address"
            autoCapitalize="none"
            value={email}
            onChangeText={setEmail}
          />

          <TouchableOpacity style={styles.submitBtn} onPress={handleSubmit} disabled={isLoading}>
            {isLoading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.submitBtnText}>Enregistrer l'événement</Text>
            )}
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  scrollContainer: {
    padding: 16,
  },
  form: {
    backgroundColor: '#fff',
    borderRadius: 16,
    padding: 20,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 4,
    elevation: 2,
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginTop: 10,
    marginBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f5f9',
    paddingBottom: 8,
  },
  label: {
    fontSize: 14,
    color: '#334155',
    marginBottom: 6,
    fontWeight: '600',
  },
  input: {
    backgroundColor: '#fff',
    color: '#0f172a',
    borderRadius: 8,
    paddingHorizontal: 16,
    paddingVertical: 10,
    fontSize: 15,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#cbd5e1',
  },
  timeRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  textArea: {
    height: 100,
    textAlignVertical: 'top',
  },
  submitBtn: {
    backgroundColor: '#f59e0b',
    borderRadius: 8,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 10,
    shadowColor: '#f59e0b',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.15,
    shadowRadius: 8,
    elevation: 3,
  },
  submitBtnText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
