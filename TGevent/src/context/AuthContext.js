import React, { createContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Audio } from 'expo-av';
import apiClient from '../api/client';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [unreadNotificationsCount, setUnreadNotificationsCount] = useState(0);
  const [notificationsList, setNotificationsList] = useState([]);

  // Charger le token et l'utilisateur enregistrés au démarrage
  useEffect(() => {
    loadStoredAuth();
  }, []);

  // Polling des notifications
  useEffect(() => {
    let intervalId;
    if (token) {
      checkNotifications();
      intervalId = setInterval(() => {
        checkNotifications();
      }, 15000); // 15 secondes
    } else {
      setUnreadNotificationsCount(0);
      setNotificationsList([]);
    }
    return () => {
      if (intervalId) clearInterval(intervalId);
    };
  }, [token]);

  const playSound = async () => {
    try {
      const { sound } = await Audio.Sound.createAsync(
        require('../../assets/notification.mp3')
      );
      await sound.playAsync();
    } catch (error) {
      console.error('Erreur lecture son de notification', error);
    }
  };

  const checkNotifications = async () => {
    try {
      const response = await apiClient.get('/notifications');
      if (response.data.status === 'success') {
        const notifs = response.data.notifications || [];
        
        // Récupérer les identifiants déjà lus localement
        const storedReadIds = await AsyncStorage.getItem('read_notification_ids');
        const readIds = storedReadIds ? JSON.parse(storedReadIds) : [];
        
        // Surcharger l'état isNew si l'ID est dans les lus localement
        const updatedNotifs = notifs.map(n => {
          if (readIds.includes(n.id)) {
            return { ...n, isNew: false };
          }
          return n;
        });

        setNotificationsList(updatedNotifs);
        
        const unread = updatedNotifs.filter(n => n.isNew).length;
        setUnreadNotificationsCount(unread);

        // Détecter les nouvelles notifications pour jouer le son
        const newNotifIds = updatedNotifs.filter(n => n.isNew).map(n => n.id);
        if (newNotifIds.length > 0) {
          const storedPlayedIds = await AsyncStorage.getItem('played_notification_ids');
          const playedIds = storedPlayedIds ? JSON.parse(storedPlayedIds) : [];
          
          const unplayedNewNotifs = newNotifIds.filter(id => !playedIds.includes(id));
          
          if (unplayedNewNotifs.length > 0) {
            await playSound();
            const updatedPlayedIds = [...playedIds, ...unplayedNewNotifs];
            await AsyncStorage.setItem('played_notification_ids', JSON.stringify(updatedPlayedIds));
          }
        }
      }
    } catch (e) {
      console.error('Erreur lors de la récupération des notifications', e);
    }
  };

  const markAllNotificationsAsRead = async () => {
    try {
      const updated = notificationsList.map(n => ({ ...n, isNew: false }));
      setNotificationsList(updated);
      setUnreadNotificationsCount(0);
      
      const allIds = notificationsList.map(n => n.id);
      await AsyncStorage.setItem('played_notification_ids', JSON.stringify(allIds));
      await AsyncStorage.setItem('read_notification_ids', JSON.stringify(allIds));
    } catch (e) {
      console.error('Erreur marquage des notifications comme lues', e);
    }
  };

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

  const updateUserProfile = async (updatedUser) => {
    try {
      await AsyncStorage.setItem('user_profile', JSON.stringify(updatedUser));
      setUser(updatedUser);
    } catch (e) {
      console.error('Erreur stockage profil mis a jour', e);
    }
  };

  return (
    <AuthContext.Provider value={{ 
      user, 
      token, 
      isLoading, 
      login, 
      register, 
      logout, 
      updateUserProfile,
      unreadNotificationsCount,
      notificationsList,
      checkNotifications,
      markAllNotificationsAsRead
    }}>
      {children}
    </AuthContext.Provider>
  );
};
