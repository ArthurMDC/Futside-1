import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  Image,
  ScrollView,
} from 'react-native';
import { useRouter } from 'expo-router';

export default function RegisterScreen() {
  const router = useRouter();
  const [name, setName] = useState('');
  const [cpf, setCpf] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [termsAccepted, setTermsAccepted] = useState(false);
  const [emailUpdates, setEmailUpdates] = useState(false);

  const handleNameChange = (text) => {
    // Remove qualquer caractere que não seja letra ou espaço
    const lettersOnly = text.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    if (lettersOnly.length <= 100) {
      setName(lettersOnly);
    }
  };

  const handleCpfChange = (text) => {
    // Remove todos os caracteres não numéricos
    const numbersOnly = text.replace(/\D/g, '');
    
    if (numbersOnly.length <= 11) {
      // Aplica a máscara do CPF (000.000.000-00)
      let formattedCpf = numbersOnly;
      if (numbersOnly.length > 9) {
        formattedCpf = `${numbersOnly.slice(0, 3)}.${numbersOnly.slice(3, 6)}.${numbersOnly.slice(6, 9)}-${numbersOnly.slice(9)}`;
      } else if (numbersOnly.length > 6) {
        formattedCpf = `${numbersOnly.slice(0, 3)}.${numbersOnly.slice(3, 6)}.${numbersOnly.slice(6)}`;
      } else if (numbersOnly.length > 3) {
        formattedCpf = `${numbersOnly.slice(0, 3)}.${numbersOnly.slice(3)}`;
      }
      setCpf(formattedCpf);
    }
  };

  const handlePhoneChange = (text) => {
    // Remove todos os caracteres não numéricos
    const numbersOnly = text.replace(/\D/g, '');
    
    if (numbersOnly.length <= 11) {
      // Aplica a máscara do telefone
      let formattedPhone = numbersOnly;
      if (numbersOnly.length > 2) {
        // Adiciona parênteses no DDD
        formattedPhone = `(${numbersOnly.slice(0, 2)})`;
        if (numbersOnly.length > 7) {
          // Formato para celular: (00) 00000-0000
          formattedPhone += ` ${numbersOnly.slice(2, 7)}-${numbersOnly.slice(7)}`;
        } else {
          // Números parciais
          formattedPhone += ` ${numbersOnly.slice(2)}`;
        }
      }
      setPhone(formattedPhone);
    }
  };

  const handleEmailChange = (text) => {
    // Limita a 150 caracteres e remove espaços
    const trimmedText = text.trim().slice(0, 150);
    setEmail(trimmedText.toLowerCase());
  };

  const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  };

  return (
    <SafeAreaView style={styles.container}>
      <TouchableOpacity
        style={styles.backButton}
        onPress={() => router.back()}
      >
        <Text style={styles.backButtonText}>✕</Text>
      </TouchableOpacity>

      <ScrollView 
        style={styles.scrollView}
        showsVerticalScrollIndicator={false}
      >
        <View style={styles.content}>
          <View style={styles.logoContainer}>
            <Image
              source={require('../assets/logo.png')}
              style={styles.logo}
              resizeMode="contain"
            />
          </View>

          <Text style={styles.greeting}>Olá, <Text style={styles.greetingHighlight}>Futuro Jogador!</Text></Text>

          <View style={styles.headerButtons}>
            <View style={styles.headerButtonContainer}>
              <Text style={styles.headerButtonActive}>Cadastre-se</Text>
              <View style={styles.activeButtonLine} />
            </View>
            <TouchableOpacity onPress={() => router.push('login')}>
              <Text style={styles.headerButtonInactive}>Login</Text>
            </TouchableOpacity>
          </View>

          <View style={styles.formContainer}>
            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Nome completo"
                placeholderTextColor="#999"
                autoCapitalize="words"
                value={name}
                onChangeText={handleNameChange}
                maxLength={100}
              />
            </View>

            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="CPF"
                placeholderTextColor="#999"
                keyboardType="numeric"
                value={cpf}
                onChangeText={handleCpfChange}
                maxLength={14} // 11 números + 2 pontos + 1 hífen
              />
            </View>

            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Telefone"
                placeholderTextColor="#999"
                keyboardType="numeric"
                value={phone}
                onChangeText={handlePhoneChange}
                maxLength={15} // (00) 00000-0000
              />
            </View>

            <View style={styles.inputContainer}>
              <TextInput
                style={[
                  styles.input,
                  email.length > 0 && !isValidEmail(email) && styles.inputError
                ]}
                placeholder="Email"
                placeholderTextColor="#999"
                keyboardType="email-address"
                autoCapitalize="none"
                value={email}
                onChangeText={handleEmailChange}
                maxLength={150}
              />
            </View>

            <View style={styles.passwordContainer}>
              <TextInput
                style={styles.input}
                placeholder="Senha"
                placeholderTextColor="#666"
                secureTextEntry={!showPassword}
              />
              <TouchableOpacity 
                style={styles.showPasswordButton}
                onPress={() => setShowPassword(!showPassword)}
              >
                <Text style={styles.showPasswordText}>Mostrar</Text>
              </TouchableOpacity>
            </View>

            <View style={styles.passwordContainer}>
              <TextInput
                style={styles.input}
                placeholder="Confirmar senha"
                placeholderTextColor="#666"
                secureTextEntry={!showConfirmPassword}
              />
              <TouchableOpacity 
                style={styles.showPasswordButton}
                onPress={() => setShowConfirmPassword(!showConfirmPassword)}
              >
                <Text style={styles.showPasswordText}>Mostrar</Text>
              </TouchableOpacity>
            </View>

            <View style={styles.checkboxContainer}>
              <TouchableOpacity 
                style={[styles.checkbox, termsAccepted && styles.checkboxChecked]}
                onPress={() => setTermsAccepted(!termsAccepted)}
              />
              <Text style={styles.checkboxLabel}>
                Eu concordo com os termos de privacidade e segurança.
              </Text>
            </View>

            <View style={styles.checkboxContainer}>
              <TouchableOpacity 
                style={[styles.checkbox, emailUpdates && styles.checkboxChecked]}
                onPress={() => setEmailUpdates(!emailUpdates)}
              />
              <Text style={styles.checkboxLabel}>
                Eu quero receber emails sobre notícias e atualizações do FutSide.
              </Text>
            </View>

            <TouchableOpacity style={styles.registerButton}>
              <Text style={styles.registerButtonText}>Cadastrar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  scrollView: {
    flex: 1,
  },
  content: {
    paddingBottom: 20,
  },
  backButton: {
    position: 'absolute',
    top: 20,
    left: 20,
    zIndex: 1,
  },
  backButtonText: {
    fontSize: 24,
    color: '#333',
  },
  logoContainer: {
    alignItems: 'center',
    marginTop: 15,
    marginBottom: 5,
  },
  logo: {
    width: 40,
    height: 40,
  },
  greeting: {
    fontSize: 22,
    color: '#333',
    paddingHorizontal: 20,
  },
  greetingHighlight: {
    color: '#4CAF50',
  },
  headerButtons: {
    flexDirection: 'row',
    paddingHorizontal: 20,
    marginTop: 5,
    gap: 20,
  },
  headerButtonContainer: {
    position: 'relative',
  },
  headerButtonActive: {
    fontSize: 16,
    color: '#333',
    fontWeight: 'bold',
  },
  activeButtonLine: {
    position: 'absolute',
    bottom: -4,
    left: 0,
    right: 0,
    height: 2,
    backgroundColor: '#4CAF50',
  },
  headerButtonInactive: {
    fontSize: 16,
    color: '#4CAF50',
  },
  formContainer: {
    padding: 20,
    marginTop: 5,
  },
  inputContainer: {
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    marginBottom: 10,
    height: 50,
  },
  input: {
    flex: 1,
    padding: 15,
    fontSize: 16,
    color: '#333',
  },
  passwordContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    marginBottom: 10,
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
  checkboxContainer: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    marginBottom: 10,
    paddingHorizontal: 5,
  },
  checkbox: {
    width: 20,
    height: 20,
    borderWidth: 1,
    borderColor: '#4CAF50',
    borderRadius: 4,
    marginRight: 10,
    marginTop: 2,
  },
  checkboxChecked: {
    backgroundColor: '#4CAF50',
  },
  checkboxLabel: {
    flex: 1,
    fontSize: 14,
    color: '#666',
    lineHeight: 20,
  },
  registerButton: {
    backgroundColor: '#4CAF50',
    padding: 15,
    borderRadius: 25,
    alignItems: 'center',
    marginTop: 10,
  },
  registerButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  inputError: {
    color: '#ff3333',
  },
}); 