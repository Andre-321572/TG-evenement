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
      <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled">
        <View style={styles.form}>
          <Text style={styles.sectionTitle}>Informations Générales</Text>

          <Text style={styles.label}>Titre de l'événement *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Concert Caritatif"
            placeholderTextColor="#6b7280"
            value={titre}
            onChangeText={setTitre}
          />

          <Text style={styles.label}>Catégorie *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Concert, Conférence, Voyage..."
            placeholderTextColor="#6b7280"
            value={categorie}
            onChangeText={setCategorie}
          />

          <Text style={styles.label}>Date *</Text>
          <TextInput
            style={styles.input}
            placeholder="Format: AAAA-MM-JJ (Ex: 2026-08-15)"
            placeholderTextColor="#6b7280"
            value={date}
            onChangeText={setDate}
          />

          <View style={styles.timeRow}>
            <View style={{ width: '48%' }}>
              <Text style={styles.label}>Heure Début *</Text>
              <TextInput
                style={styles.input}
                placeholder="Ex: 19:00"
                placeholderTextColor="#6b7280"
                value={startHeure}
                onChangeText={setStartHeure}
              />
            </View>
            <View style={{ width: '48%' }}>
              <Text style={styles.label}>Heure Fin *</Text>
              <TextInput
                style={styles.input}
                placeholder="Ex: 22:30"
                placeholderTextColor="#6b7280"
                value={endHeure}
                onChangeText={setEndHeure}
              />
            </View>
          </View>

          <Text style={styles.label}>Lieu *</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: Grand Palais, Lomé"
            placeholderTextColor="#6b7280"
            value={lieu}
            onChangeText={setLieu}
          />

          <Text style={styles.label}>Description</Text>
          <TextInput
            style={[styles.input, styles.textArea]}
            placeholder="Décrivez l'événement..."
            placeholderTextColor="#6b7280"
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
            placeholderTextColor="#6b7280"
            value={nomProprietaire}
            onChangeText={setNomProprietaire}
          />

          <Text style={styles.label}>Téléphone de contact</Text>
          <TextInput
            style={styles.input}
            placeholder="Ex: 90102030"
            placeholderTextColor="#6b7280"
            keyboardType="phone-pad"
            value={telephone}
            onChangeText={setTelephone}
          />

          <Text style={styles.label}>Email de contact</Text>
          <TextInput
            style={styles.input}
            placeholder="contact@evenement.com"
            placeholderTextColor="#6b7280"
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
    backgroundColor: '#111827',
  },
  scrollContainer: {
    padding: 16,
  },
  form: {
    backgroundColor: '#1f2937',
    borderRadius: 12,
    padding: 20,
    borderWidth: 1,
    borderColor: '#374151',
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#f59e0b',
    marginTop: 10,
    marginBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#374151',
    paddingBottom: 8,
  },
  label: {
    fontSize: 14,
    color: '#d1d5db',
    marginBottom: 6,
    fontWeight: '500',
  },
  input: {
    backgroundColor: '#374151',
    color: '#fff',
    borderRadius: 8,
    paddingHorizontal: 16,
    paddingVertical: 10,
    fontSize: 15,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#4b5563',
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
  },
  submitBtnText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
