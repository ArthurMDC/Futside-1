# FutSide App

Aplicativo mobile desenvolvido com React Native e Expo Go para gerenciamento de jogadores de futebol.

## Pré-requisitos

- Node.js (versão 14 ou superior)
- npm ou yarn
- Expo Go instalado no seu dispositivo móvel

## Instalação

1. Clone o repositório:
```bash
git clone [seu-repositorio]
cd futside-app
```

2. Instale as dependências:
```bash
npm install
# ou
yarn install
```

## Executando o projeto

1. Inicie o servidor de desenvolvimento:
```bash
npm start
# ou
yarn start
```

2. Escaneie o QR Code com o aplicativo Expo Go no seu dispositivo móvel ou execute em um emulador.

## Estrutura do projeto

```
futside-app/
├── App.js
├── screens/
│   ├── LoginScreen.js
│   ├── ForgotPasswordScreen.js
│   └── RegisterScreen.js
├── assets/
│   └── logo.png
└── package.json
```

## Funcionalidades

- Tela de login com autenticação via email/senha
- Login social com Google e Facebook
- Recuperação de senha
- Cadastro de novo usuário
- Navegação entre telas 