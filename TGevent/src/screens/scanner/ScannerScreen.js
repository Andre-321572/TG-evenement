import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Button, TouchableOpacity, ActivityIndicator, Alert } from 'react-native';
import { CameraView, useCameraPermissions } from 'expo-camera';
import apiClient from '../../api/client';

export default function ScannerScreen() {
  const [permission, requestPermission] = useCameraPermissions();
  const [scanned, setScanned] = useState(false);
  const [scanResult, setScanResult] = useState(null);
  const [isValidating, setIsValidating] = useState(false);

  if (!permission) {
    // Permission de la caméra en cours de chargement
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  if (!permission.granted) {
    // Permission de la caméra non accordée
    return (
      <View style={styles.permissionContainer}>
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
          <View style={styles.overlay}>
            <View style={styles.unfocusedContainer}></View>
            <View style={styles.middleContainer}>
              <View style={styles.unfocusedContainer}></View>
              <View style={styles.focusedContainer}>
                {isValidating && (
                  <ActivityIndicator size="large" color="#3b82f6" />
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
          <Text style={styles.resultTitle}>
            {scanResult.status === 'valid' && 'Billet Valide ✓'}
            {scanResult.status === 'already_scanned' && 'Déjà Scanné ⚠️'}
            {scanResult.status === 'invalid' && 'Billet Invalide ✗'}
            {scanResult.status === 'error' && 'Erreur !'}
          </Text>

          <Text style={styles.resultMessage}>{scanResult.message}</Text>

          {scanResult.ticket && (
            <View style={styles.ticketDetails}>
              <Text style={styles.detailText}>👤 Acheteur : {scanResult.ticket.buyer_name}</Text>
              <Text style={styles.detailText}>🎫 Type : {scanResult.ticket.billet_type}</Text>
              <Text style={styles.detailText}>📅 Événement : {scanResult.ticket.evenement}</Text>
              <Text style={styles.detailText}>📍 Lieu : {scanResult.ticket.lieu}</Text>
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
    backgroundColor: '#111827',
  },
  permissionContainer: {
    flex: 1,
    backgroundColor: '#111827',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  permissionText: {
    color: '#9ca3af',
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 24,
    lineHeight: 24,
  },
  permissionButton: {
    backgroundColor: '#3b82f6',
    borderRadius: 8,
    paddingHorizontal: 20,
    paddingVertical: 12,
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
    backgroundColor: 'rgba(0,0,0,0.7)',
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
    backgroundColor: '#064e3b',
  },
  resultAlreadyScanned: {
    backgroundColor: '#78350f',
  },
  resultInvalid: {
    backgroundColor: '#7f1d1d',
  },
  resultTitle: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 16,
  },
  resultMessage: {
    fontSize: 18,
    color: '#fff',
    textAlign: 'center',
    marginBottom: 30,
    paddingHorizontal: 16,
  },
  ticketDetails: {
    backgroundColor: 'rgba(0,0,0,0.3)',
    borderRadius: 12,
    padding: 20,
    width: '100%',
    marginBottom: 40,
  },
  detailText: {
    color: '#fff',
    fontSize: 16,
    marginBottom: 8,
    fontWeight: '500',
  },
  scanAgainButton: {
    backgroundColor: '#fff',
    borderRadius: 8,
    paddingHorizontal: 24,
    paddingVertical: 14,
    width: '100%',
    alignItems: 'center',
  },
  scanAgainButtonText: {
    color: '#111827',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
