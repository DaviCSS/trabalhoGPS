# Crochettei

Marketplace desenvolvido para conectar artesãs de crochê do Espírito Santo a clientes em todo o Brasil. O sistema permite que cada artesã gerencie sua própria vitrine de produtos — com controle de estoque, pedidos e encomendas — enquanto os clientes navegam, compram e acompanham suas entregas em um único ambiente.

Desenvolvido com **CodeIgniter 4**, estruturado seguindo o padrão **MVC** com separação explícita de casos de uso (*Use Cases*), e persistência em **MySQL**.

---

## Tecnologias Utilizadas

| Camada | Tecnologia |
|--------|------------|
| Back-end | PHP 8.1 + CodeIgniter 4 |
| Banco de Dados | MySQL 8 via MySQLi |
| Front-end | HTML5, CSS3 (Vanilla), JavaScript (ES6+) |
| Servidor local | XAMPP / Apache |
| Gerenciamento de dependências | Composer |

---

## Pré-requisitos

Antes de iniciar, certifique-se de ter instalado na máquina:

- [XAMPP](https://www.apachefriends.org/) com PHP **8.0 ou superior** e MySQL
- [Composer](https://getcomposer.org/)

---

## Instalação

### 1. Extrair o projeto

Descompacte o arquivo `.zip` recebido dentro da pasta `htdocs` do XAMPP. O caminho final deve ser:

```
C:\xampp\htdocs\crochettei\
```

### 2. Instalar as dependências

Dentro da pasta do projeto, execute:

```bash
composer install
```

### 3. Configurar o ambiente

Na raiz do projeto, copie o arquivo `env` e renomeie a cópia para `.env`:

```bash
cp env .env
```

Abra o `.env` e ajuste as seguintes linhas:

```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = crochettei
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

> Deixe `password` em branco se o seu MySQL local não tiver senha configurada (padrão do XAMPP).

### 4. Criar o banco de dados

Acesse o `phpMyAdmin` (`http://localhost/phpmyadmin`) e crie um banco de dados com o nome **`crochettei`**, com collation `utf8mb4_unicode_ci`.

### 5. Rodar as Migrations e Seeders

Com o banco criado, execute no terminal (dentro da pasta do projeto):

```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

O comando `migrate` cria todas as tabelas. O `db:seed` popula o banco com dados iniciais de demonstração (usuários, artesãs, produtos e categorias).

### 6. Iniciar o servidor

```bash
php spark serve
```

Acesse em: **http://localhost:8080**

---

## Credenciais de Acesso (Dados de Demonstração)

Após rodar o seeder, os seguintes usuários estarão disponíveis:

| Perfil | E-mail | Senha |
|---|---|---|
| Administrador | admin@crochettei.com | 123456 |
| Artesã | artesa@crochettei.com | 123456 |
| Cliente | cliente@crochettei.com | 123456 |


Caso queira, pode cadastrar novos usuários no sistema.

---
