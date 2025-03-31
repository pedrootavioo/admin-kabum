# 🛡️ Portal Administrativo - KaBuM

Sistema administrativo desenvolvido em **PHP puro**, com estrutura modular, desacoplada e baseada nos princípios **SOLID**.

---

## 🌟 Objetivo

Permitir o gerenciamento completo de **clientes** (CRUD), acessado por **usuários autenticados** com login e senha.

---

## ⚙️ Tecnologias Utilizadas

- **Back-end:** PHP 8.2+
- **Banco de Dados:** MySQL
- **Front-end:** Bootstrap 5 + Vite
- **Testes:** Pest PHP
- **Gerenciadores:** Composer (dependências PHP) e NPM (assets frontend)

---

## 🚀 Como executar o projeto

### 1. Clone o repositório

```bash
git clone https://github.com/pedrootavioo/admin-kabum.git
cd admin-kabum
```

### 2. Instale as dependências

```bash
composer install
npm install && npm run build
```

### 3. Configure o ambiente

#### 🔹 Utilizando o instalador automático (recomendado)

Acesse o projeto pelo navegador (localhost ou servidor) e siga as instruções na tela.  
O instalador solicitará:

- Dados do banco de dados
- Dados do Guardião (usuário admin)

#### 🔹 Configuração manual

1. Copie o arquivo `.env.example` para `.env`
2. Preencha as variáveis conforme necessário
3. Importe o arquivo `db.sql` no seu banco de dados MySQL

>#### 💡 OPCIONAL:  Um banco de dados hospedado em uma VPS foi disponibilizado para testes.  
> Os dados estão previamente preenchidos de forma extraordinária, via input, na instalação automática.

> ##### ⚠️ AVISO: CREDENCIAIS DE TESTE PARA AGILIDADE NA CANDIDATURA
> **Importante:** Este repositório contém IP, senhas e usuários PARA TESTES EXCLUSIVAMENTE, utilizados para agilizar a demonstração e teste durante o processo de candidatura.
> 
> **Sem risco:** Essas credenciais NÃO dão acesso a dados reais ou sistemas em produção.
> 
> **Projeto real:** JAMAIS commito credenciais no código.

---

### 4. Acesse o sistema

```text
URL: http://localhost (ou domínio configurado)
Usuário: (definido na instalação)
Senha: (definida na instalação)
```

---

## 🔮 Testes Automatizados com Pest

O projeto utiliza o framework **Pest PHP** para testes.

### ⚖️ Executar todos os testes
```bash
./vendor/bin/pest
```

### 💡 Testes implementados
- Criação de pessoa com dados válidos
- Validações de campos obrigatórios (CPF, nome, data)
- Relacionamento de usuário com pessoa

> Os testes podem ser encontrados na pasta `/tests/`

---

## 📂 Estrutura de Pastas

```
admin-kabum/
├── public/               # Página pública inicial
├── source/
│   ├── Core/             # Infraestrutura (Router, DB, View, Session...)
│   ├── Controllers/      # Controladores (rotas e lógica de aplicação)
│   ├── Models/           # Models com regras de negócio e ORM customizado
│   ├── Support/          # Helpers e utilitários diversos
│   ├── Services/         # Validações externas (CPF, datas, etc.)
│   └── Views/            # Views do sistema com templates Bootstrap
├── tests/                # Testes com Pest PHP
├── db.sql                # Script para criação do banco de dados
└── .env                  # Arquivo de variáveis de ambiente
```

---

Feito com ❤️ por [Pedro Otávio](https://github.com/pedrootavioo)
