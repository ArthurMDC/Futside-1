// Configuração da API
export const API_URL = 'https://lightsalmon-ibex-601706.hostingersite.com/api'; // URL do seu servidor

// Configurações padrão para as requisições
export const defaultHeaders = {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
};

// Endpoints da API
export const endpoints = {
  login: '/login.php',
  register: '/register.php',
  test: '/test.php'
};

// Função para tratar erros da API
export const handleApiError = (error) => {
  console.error('Erro na API:', error);
  throw new Error(error.message || 'Erro ao conectar com o servidor');
};

// Função para fazer requisições à API
export const apiRequest = async (endpoint, options = {}) => {
  try {
    const response = await fetch(`${API_URL}${endpoint}`, {
      ...options,
      headers: {
        ...defaultHeaders,
        ...options.headers,
      },
    });

    const data = await response.json();

    if (!response.ok || !data.success) {
      throw new Error(data.error || 'Erro na requisição');
    }

    return data;
  } catch (error) {
    handleApiError(error);
  }
};

// Funções de autenticação
export const auth = {
  login: async (email, password) => {
    return apiRequest(endpoints.login, {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });
  },
  
  register: async (name, email, password) => {
    return apiRequest(endpoints.register, {
      method: 'POST',
      body: JSON.stringify({ name, email, password })
    });
  }
}; 