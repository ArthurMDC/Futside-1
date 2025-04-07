import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  Image,
  Linking,
  Alert,
} from 'react-native';
import { useRouter } from 'expo-router';
import { apiRequest } from '../config/api';

export default function LoginScreen() {
  const router = useRouter();
  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert('Erro', 'Por favor, preencha todos os campos');
      return;
    }

    try {
      setLoading(true);
      const data = await apiRequest('/login.php', {
        method: 'POST',
        body: JSON.stringify({
          email,
          password,
        }),
      });

      if (data.success) {
        // Redirecionando para a tela de sucesso
        router.replace('login-success');
      } else {
        Alert.alert('Erro', data.error || 'Erro ao fazer login');
      }
    } catch (error) {
      Alert.alert('Erro', error.message || 'Erro ao conectar com o servidor');
    } finally {
      setLoading(false);
    }
  };

  const handleGooglePress = async () => {
    try {
      await Linking.openURL('https://accounts.google.com');
    } catch (error) {
      console.error('Erro ao abrir o Gmail:', error);
    }
  };

  const handleFacebookPress = async () => {
    try {
      await Linking.openURL('https://www.facebook.com');
    } catch (error) {
      console.error('Erro ao abrir o Facebook:', error);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <TouchableOpacity
        style={styles.backButton}
        onPress={() => router.back()}
      >
        <Text style={styles.backButtonText}>✕</Text>
      </TouchableOpacity>

      <View style={styles.logoContainer}>
        <Image
          source={require('../assets/logo.png')}
          style={styles.logo}
          resizeMode="contain"
        />
      </View>

      <View style={styles.headerContainer}>
        <Text style={styles.greeting}>Olá, <Text style={styles.greetingHighlight}>Jogador!</Text></Text>
        <View style={styles.headerButtons}>
          <Text style={styles.headerButtonActive}>Entrar</Text>
          <TouchableOpacity onPress={() => router.push('register')}>
            <Text style={styles.headerButtonInactive}>Cadastrar</Text>
          </TouchableOpacity>
        </View>
      </View>

      <View style={styles.formContainer}>
        <View style={styles.inputContainer}>
          <TextInput
            style={styles.input}
            placeholder="Email"
            placeholderTextColor="#999"
            keyboardType="email-address"
            autoCapitalize="none"
            value={email}
            onChangeText={setEmail}
          />
        </View>

        <View style={styles.passwordContainer}>
          <TextInput
            style={styles.input}
            placeholder="Senha"
            placeholderTextColor="#999"
            secureTextEntry={!showPassword}
            value={password}
            onChangeText={setPassword}
          />
          <TouchableOpacity 
            style={styles.showPasswordButton}
            onPress={() => setShowPassword(!showPassword)}
          >
            <Text style={styles.showPasswordText}>Mostrar</Text>
          </TouchableOpacity>
        </View>

        <TouchableOpacity 
          style={styles.forgotPassword}
          onPress={() => router.push('/forgot-password')}
        >
          <Text style={styles.forgotPasswordText}>Esqueceu a senha?</Text>
        </TouchableOpacity>

        <TouchableOpacity 
          style={[styles.loginButton, loading && styles.loginButtonDisabled]}
          onPress={handleLogin}
          disabled={loading}
        >
          <Text style={styles.loginButtonText}>{loading ? 'Carregando...' : 'Entrar'}</Text>
        </TouchableOpacity>

        <View style={styles.socialContainer}>
          <Text style={styles.socialText}>Entre com</Text>
          <View style={styles.socialButtons}>
            <TouchableOpacity 
              style={styles.socialButton}
              onPress={handleGooglePress}
            >
              <Text style={styles.socialButtonText}>Google</Text>
            </TouchableOpacity>
            <TouchableOpacity 
              style={styles.socialButton}
              onPress={handleFacebookPress}
            >
              <Text style={styles.socialButtonText}>Facebook</Text>
            </TouchableOpacity>
          </View>
        </View>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  backButton: {
    position: 'absolute',
    top: 40,
    left: 20,
    zIndex: 1,
  },
  backButtonText: {
    fontSize: 24,
    color: '#333',
  },
  logoContainer: {
    alignItems: 'center',
    marginTop: 60,
  },
  logo: {
    width: 80,
    height: 80,
  },
  headerContainer: {
    paddingHorizontal: 20,
  },
  greeting: {
    fontSize: 24,
    color: '#333',
  },
  greetingHighlight: {
    color: '#4CAF50',
  },
  headerButtons: {
    flexDirection: 'row',
    marginTop: 20,
    gap: 20,
  },
  headerButtonActive: {
    fontSize: 16,
    color: '#333',
    fontWeight: 'bold',
    borderBottomWidth: 2,
    borderBottomColor: '#4CAF50',
    paddingBottom: 5,
  },
  headerButtonInactive: {
    fontSize: 16,
    color: '#999',
  },
  formContainer: {
    padding: 20,
    marginTop: 20,
  },
  inputContainer: {
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    marginBottom: 15,
    height: 50,
  },
  passwordContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    marginBottom: 15,
    height: 50,
  },
  showPasswordButton: {
    paddingHorizontal: 15,
    height: '100%',
    justifyContent: 'center',
  },
  showPasswordText: {
    color: '#4CAF50',
    fontSize: 14,
  },
  input: {
    flex: 1,
    padding: 15,
    fontSize: 16,
    color: '#333',
  },
  forgotPassword: {
    alignSelf: 'flex-end',
    marginBottom: 20,
  },
  forgotPasswordText: {
    color: '#4CAF50',
    fontSize: 14,
  },
  loginButton: {
    backgroundColor: '#4CAF50',
    padding: 15,
    borderRadius: 8,
    alignItems: 'center',
  },
  loginButtonDisabled: {
    backgroundColor: '#ddd',
  },
  loginButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  socialContainer: {
    marginTop: 30,
    alignItems: 'center',
  },
  socialText: {
    color: '#999',
    marginBottom: 15,
  },
  socialButtons: {
    flexDirection: 'row',
    gap: 20,
  },
  socialButton: {
    padding: 15,
    paddingHorizontal: 30,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: '#ddd',
  },
  socialButtonText: {
    color: '#333',
    fontSize: 14,
    fontWeight: '500',
  },
}); 