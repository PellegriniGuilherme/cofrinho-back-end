# Cofrinho · Back-end

Este é o back-end do Cofrinho, um sistema de controle financeiro pessoal construído com Laravel 11 e Docker (Sail). Ele oferece autenticação segura, gerenciamento de contas, transações e categorias.

> 🔗 Front-end disponível em: [https://github.com/PellegriniGuilherme/cofrinho-front-end](https://github.com/PellegriniGuilherme/cofrinho-front-end)

---

## 🚀 Tecnologias Utilizadas

- [Laravel 12](https://laravel.com/)
- [Laravel Sail (Docker)](https://laravel.com/docs/sail)
- [MySQL](https://www.mysql.com/)
- [PHP 8.3](https://www.php.net/)

---

## ⚙️ Instalação com Laravel Sail

### 1. Clonar o repositório

```bash
git clone https://github.com/PellegriniGuilherme/cofrinho-back-end.git
cd cofrinho-back-end
```

### 2. Copiar o arquivo de ambiente e configurar

```bash
cp .env.example .env
```

Você pode ajustar as configurações do banco de dados, como nome, usuário e senha no `.env`.

### 3. Subir os containers com Sail

```bash
./vendor/bin/sail up -d --build
```

### 4. Gerar a chave da aplicação e rodar as migrations

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

> 📌 **Observação:** Caso você tenha erros ao rodar o Sail, certifique-se de ter o Docker Desktop instalado e em funcionamento.

---

## 🔐 Autenticação

A autenticação do Cofrinho utiliza o sistema padrão do Laravel com cookies HTTPOnly:

Após o login, o Laravel gera automaticamente um cookie de sessão seguro.

Esse cookie é armazenado no navegador e enviado automaticamente em cada requisição subsequente.

As rotas protegidas exigem autenticação via middleware padrão auth.

> ⚠️ Para que o front-end consiga acessar essas rotas, é necessário que as requisições sejam feitas com credentials: 'include' e que o back-end esteja corretamente configurado para CORS com suporte a cookies.

---

## 📁 Estrutura das Principais Features

```
cofrinho-back-end/
├── app/
│   ├── Http/Controllers/        # Controllers das APIs
│   ├── Models/                  # Models principais
│   ├── Services/                # Regras de negócio
│   ├── Traits/                  # Traits reutilizáveis
│   └── Enums/                   # Tipagens específicas
├── database/
│   ├── migrations/              # Estrutura do banco de dados
│   └── seeders/                 # Dados iniciais
├── routes/
│   └── api.php                  # Rotas de API
└── docker-compose.yml          # Configuração Docker (via Sail)
```
---

## 📝 Licença

Este projeto está sob a licença MIT.