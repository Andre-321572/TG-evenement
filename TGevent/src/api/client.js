import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Remplacer par l'adresse IP locale de votre machine ou domaine en production
// http://10.0.2.2:8000 est l'adresse par défaut de l'hôte dans l'émulateur Android
const API_URL = 'http://10.0.2.2:8000/api';

const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  timeout: 10000,
});

// Intercepteur pour injecter automatiquement le jeton d'authentification
apiClient.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('user_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default apiClient;
export { API_URL };
