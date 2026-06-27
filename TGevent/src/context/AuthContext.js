import React, { createContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import apiClient from '../api/client';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  // Charger le token et l'utilisateur enregistrés au démarrage
  useEffect(() => {
    loadStoredAuth();
  }, []);

  const loadStoredAuth = async () => {
    try {
      const storedToken = await AsyncStorage.getItem('user_token');
      const storedUser = await AsyncStorage.getItem('user_profile');
      if (storedToken && storedUser) {
        setToken(storedToken);
        setUser(JSON.parse(storedUser));
      }
    } catch (e) {
      console.error('Erreur lors du chargement des identifiants', e);
    } finally {
      setIsLoading(false);
    }
  };

  const login = async (loginInput, password) => {
    setIsLoading(true);
    try {
      const response = await apiClient.post('/auth/login', {
        login: loginInput,
        password,
      });

      if (response.data.status === 'success') {
        const { token: userToken, user: userProfile } = response.data;
        await AsyncStorage.setItem('user_token', userToken);
        await AsyncStorage.setItem('user_profile', JSON.stringify(userProfile));
        setToken(userToken);
        setUser(userProfile);
        return { success: true };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Identifiants ou connexion invalides.',
      };
    } finally {
      setIsLoading(false);
    }
  };

  const register = async (userData) => {
    setIsLoading(true);
    try {
      const response = await apiClient.post('/auth/register', userData);
      if (response.data.status === 'success') {
        const { token: userToken, user: userProfile } = response.data;
        await AsyncStorage.setItem('user_token', userToken);
        await AsyncStorage.setItem('user_profile', JSON.stringify(userProfile));
        setToken(userToken);
        setUser(userProfile);
        return { success: true };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de l\'inscription.',
        errors: error.response?.data?.errors || null,
      };
    } finally {
      setIsLoading(false);
    }
  };

  const logout = async () => {
    setIsLoading(true);
    try {
      await apiClient.post('/auth/logout');
    } catch (e) {
      console.warn('Erreur déconnexion serveur (ignorée en local)', e);
    } finally {
      await AsyncStorage.removeItem('user_token');
      await AsyncStorage.removeItem('user_profile');
      setToken(null);
      setUser(null);
      setIsLoading(false);
    }
  };

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
