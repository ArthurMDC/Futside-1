import React from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  Image,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';

export default function RegisterScreen() {
  const navigation = useNavigation();

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.logoContainer}>
        <Image
          source={require('../assets/logo.png')}
          style={styles.logo}
          resizeMode="contain"
        />
        <Text style={styles.title}>Olá, Futuro Jogador!</Text>
      </View>

      <View style={styles.formContainer}>
        <TextInput
          style={styles.input}
          placeholder="Nome"
          placeholderTextColor="#999"
        />
        <TextInput
          style={styles.input}
          placeholder="000.000.000-94"
          placeholderTextColor="#999"
          keyboardType="numeric"
        />
        <TextInput
          style={styles.input}
          placeholder="Telefone (00) 0000-0000"
          placeholderTextColor="#999"
          keyboardType="phone-pad"
        />
        <TextInput
          style={styles.input}
          placeholder="exemplo@email.com"
          placeholderTextColor="#999"
          keyboardType="email-address"
          autoCapitalize="none"
        />
        <TextInput
          style={styles.input}
          placeholder="Senha"
          placeholderTextColor="#999"
          secureTextEntry
        />
        <TextInput
          style={styles.input}
          placeholder="Confirmar Senha"
          placeholderTextColor="#999"
          secureTextEntry
        />

        <Text style={styles.termsText}>
          Eu concordo com os termos de privacidade e segurança.
        </Text>
        <Text style={styles.newsletterText}>
          Eu quero receber emails sobre notícias e atualizações da FutSide
        </Text>

        <TouchableOpacity style={styles.registerButton}>
          <Text style={styles.registerButtonText}>Cadastrar</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.loginLink}
          onPress={() => navigation.navigate('Login')}
        >
          <Text style={styles.loginLinkText}>Login</Text>
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  logoContainer: {
    alignItems: 'center',
    marginTop: 40,
  },
  logo: {
    width: 120,
    height: 120,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginTop: 20,
    color: '#333',
  },
  formContainer: {
    padding: 20,
    marginTop: 10,
  },
  input: {
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    padding: 15,
    marginBottom: 10,
    fontSize: 16,
  },
  termsText: {
    fontSize: 12,
    color: '#666',
    marginTop: 10,
  },
  newsletterText: {
    fontSize: 12,
    color: '#666',
    marginTop: 5,
    marginBottom: 20,
  },
  registerButton: {
    backgroundColor: '#4CAF50',
    padding: 15,
    borderRadius: 8,
    alignItems: 'center',
  },
  registerButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  loginLink: {
    marginTop: 15,
    alignItems: 'center',
  },
  loginLinkText: {
    color: '#4CAF50',
    fontSize: 16,
  },
}); 