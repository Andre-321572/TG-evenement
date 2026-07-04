import React, { useState, useEffect, useContext } from 'react';
import { StyleSheet, Text, View, Button, TouchableOpacity, ActivityIndicator, Alert } from 'react-native';
import { CameraView, useCameraPermissions } from 'expo-camera';
import { Ionicons } from '@expo/vector-icons';
import { AuthContext } from '../../context/AuthContext';
import apiClient from '../../api/client';

export default function ScannerScreen() {
  const { user } = useContext(AuthContext);
  const [permission, requestPermission] = useCameraPermissions();
  const [scanned, setScanned] = useState(false);
  const [scanResult, setScanResult] = useState(null);
  const [isValidating, setIsValidating] = useState(false);

  if (!permission) {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#2563eb" />
      </View>
    );
  }

  if (!permission.granted) {
    return (
      <View style={styles.permissionContainer}>
        <Ionicons name="camera-outline" size={64} color="#94a3b8" style={{ marginBottom: 16 }} />
        <Text style={styles.permissionText}>Nous avons besoin de votre autorisation pour accéder à l'appareil photo afin de scanner les billets.</Text>
        <TouchableOpacity style={styles.permissionButton} onPress={requestPermission}>
          <Text style={styles.permissionButtonText}>Autoriser la caméra</Text>
        </TouchableOpacity>
      </View>
    );
  }

  const handleBarcodeScanned = async ({ type, data }) => {
    if (scanned || isValidating) return;
    setScanned(true);
    setIsValidating(true);
    setScanResult(null);

    try {
      const response = await apiClient.post('/scanner/verify', { code: data });
      const result = response.data;
      setScanResult(result);
    } catch (error) {
      if (error.response && error.response.status === 404) {
        setScanResult({
          status: 'invalid',
          message: 'Code billet invalide.',
        });
      } else {
        setScanResult({
          status: 'error',
          message: 'Erreur réseau ou connexion serveur impossible.',
        });
      }
    } finally {
      setIsValidating(false);
    }
  };

  const resetScan = () => {
    setScanned(false);
    setScanResult(null);
  };

  return (
    <View style={styles.container}>
      {!scanResult ? (
        <CameraView
          style={StyleSheet.absoluteFillObject}
          onBarcodeScanned={scanned ? undefined : handleBarcodeScanned}
          barcodeScannerSettings={{
            barcodeTypes: ['qr'],
          }}
        >
          {/* Scanner Header Banner showing assigned event */}
          <View style={styles.scannerHeader}>
            <Text style={styles.scannerTitle}>Interface de Scan</Text>
            <View style={styles.assignedEventBadge}>
              <Ionicons 
                name={user?.assigned_evenement ? "checkmark-circle-outline" : "globe-outline"} 
                size={14} 
                color="#ffffff" 
                style={{ marginRight: 6 }} 
              />
              <Text style={styles.assignedEventText} numberOfLines={1}>
                {user?.assigned_evenement 
                  ? `Événement : ${user.assigned_evenement.titre}` 
                  : "Scanner Universel (Tous les événements)"}
              </Text>
            </View>
          </View>

          <View style={styles.overlay}>
            <View style={styles.unfocusedContainer}></View>
            <View style={styles.middleContainer}>
              <View style={styles.unfocusedContainer}></View>
              <View style={styles.focusedContainer}>
                {isValidating && (
                  <ActivityIndicator size="large" color="#2563eb" />
                )}
              </View>
              <View style={styles.unfocusedContainer}></View>
            </View>
            <View style={styles.unfocusedContainer}></View>
          </View>
        </CameraView>
      ) : (
        <View
          style={[
            styles.resultContainer,
            scanResult.status === 'valid' && styles.resultValid,
            scanResult.status === 'already_scanned' && styles.resultAlreadyScanned,
            (scanResult.status === 'invalid' || scanResult.status === 'error') && styles.resultInvalid,
          ]}
        >
          <Ionicons 
            name={
              scanResult.status === 'valid' ? 'checkmark-circle-outline' :
              scanResult.status === 'already_scanned' ? 'warning-outline' : 'close-circle-outline'
            } 
            size={80} 
            color="#fff" 
            style={{ marginBottom: 16 }}
          />

          <Text style={styles.resultTitle}>
            {scanResult.status === 'valid' && 'Billet Valide ✓'}
            {scanResult.status === 'already_scanned' && 'Déjà Scanné ⚠️'}
            {scanResult.status === 'invalid' && 'Billet Invalide ✗'}
            {scanResult.status === 'error' && 'Erreur !'}
          </Text>

          <Text style={styles.resultMessage}>{scanResult.message}</Text>

          {scanResult.ticket && (
            <View style={styles.ticketDetails}>
              <Text style={styles.detailText}>
                <Ionicons name="person-outline" size={14} color="#64748b" /> Acheteur : {scanResult.ticket.buyer_name}
              </Text>
              <Text style={styles.detailText}>
                <Ionicons name="ticket-outline" size={14} color="#64748b" /> Type : {scanResult.ticket.billet_type}
              </Text>
              <Text style={styles.detailText}>
                <Ionicons name="calendar-outline" size={14} color="#64748b" /> Événement : {scanResult.ticket.evenement}
              </Text>
              <Text style={styles.detailText}>
                <Ionicons name="location-outline" size={14} color="#64748b" /> Lieu : {scanResult.ticket.lieu}
              </Text>
            </View>
          )}

          <TouchableOpacity style={styles.scanAgainButton} onPress={resetScan}>
            <Text style={styles.scanAgainButtonText}>Scanner un autre billet</Text>
          </TouchableOpacity>
        </View>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  permissionContainer: {
    flex: 1,
    backgroundColor: '#f8fafc',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  permissionText: {
    color: '#64748b',
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 24,
    lineHeight: 24,
  },
  permissionButton: {
    backgroundColor: '#2563eb',
    borderRadius: 12,
    paddingHorizontal: 24,
    paddingVertical: 14,
    shadowColor: '#2563eb',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 3,
  },
  permissionButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  overlay: {
    flex: 1,
    flexDirection: 'column',
  },
  unfocusedContainer: {
    flex: 1,
    backgroundColor: 'rgba(15, 23, 42, 0.7)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  middleContainer: {
    flexDirection: 'row',
    height: 250,
  },
  focusedContainer: {
    width: 250,
    borderWidth: 2,
    borderColor: '#3b82f6',
    borderRadius: 16,
    backgroundColor: 'transparent',
    justifyContent: 'center',
    alignItems: 'center',
  },
  resultContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  resultValid: {
    backgroundColor: '#059669',
  },
  resultAlreadyScanned: {
    backgroundColor: '#d97706',
  },
  resultInvalid: {
    backgroundColor: '#dc2626',
  },
  resultTitle: {
    fontSize: 30,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 12,
  },
  resultMessage: {
    fontSize: 17,
    color: '#fff',
    textAlign: 'center',
    marginBottom: 30,
    paddingHorizontal: 16,
    lineHeight: 22,
  },
  ticketDetails: {
    backgroundColor: 'rgba(255,255,255,0.15)',
    borderRadius: 16,
    padding: 20,
    width: '100%',
    marginBottom: 40,
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,0.2)',
  },
  detailText: {
    color: '#fff',
    fontSize: 15,
    marginBottom: 8,
    fontWeight: '600',
  },
  scanAgainButton: {
    backgroundColor: '#fff',
    borderRadius: 12,
    paddingHorizontal: 24,
    paddingVertical: 14,
    width: '100%',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 3,
  },
  scanAgainButtonText: {
    color: '#0f172a',
    fontSize: 16,
    fontWeight: 'bold',
  },
  scannerHeader: {
    position: 'absolute',
    top: 50,
    left: 20,
    right: 20,
    backgroundColor: 'rgba(15, 23, 42, 0.85)',
    borderRadius: 12,
    padding: 14,
    alignItems: 'center',
    zIndex: 10,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.15)',
  },
  scannerTitle: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: 'bold',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginBottom: 4,
  },
  assignedEventBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(37, 99, 235, 0.3)',
    borderRadius: 6,
    paddingVertical: 4,
    paddingHorizontal: 10,
    marginTop: 2,
    maxWidth: '100%',
  },
  assignedEventText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '600',
  },
});
