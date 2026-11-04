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

---

## Módulo Financeiro — PBIs 01, 02, 03, 07, 11, 14 e 15

Este módulo acrescenta o painel de receitas e cadastro rápido de vendas, cobrança Pix direta, histórico por período, exportação em texto e orientações educativas para MEI.

- **Painel da artesã:** em `/artesa/dashboard`, consulte o saldo recebido, o resumo do mês e registre uma venda realizada fora do site.
- **Configurar Pix:** em `/artesa/apoio`, cadastre uma chave Pix e a cidade da artesã antes de receber pedidos com Pix.
- **Pagamento:** ao finalizar a compra, o cliente encontra QR Code e Pix Copia e Cola em `/cliente/pedidos`.
- **Confirmação manual:** a artesã confirma o recebimento em `/artesa/pedidos`. Pagamentos pendentes não contam como receita e não liberam a entrega.
- **Histórico:** em `/artesa/relatorio`, filtre por semana, mês atual, mês anterior, mês específico ou todo o período.
- **Exportação:** o relatório é baixado em `.txt` (sem dependências pagas), com o mesmo filtro aplicado na tela.
- **MEI:** ao alcançar a meta demonstrativa de dez vendas recebidas, o painel apresenta passos educativos de formalização. Essa meta não é um limite legal.

### Atualização do banco

Depois de atualizar o código, rode a migration adicional, preservando os registros existentes:

```bash
php spark migrate
```

### Verificação manual

1. Faça login como artesã, acesse as configurações e cadastre uma chave Pix válida.
2. Registre uma venda manual e confira a atualização do painel e do relatório.
3. Entre como cliente, faça um pedido Pix e confira o valor, o QR Code e o código Copia e Cola.
4. Verifique que o pedido está pendente e ainda não aparece no total recebido.
5. Depois de conferir o recebimento no aplicativo bancário, confirme o pagamento na área de pedidos da artesã.
6. Filtre o histórico por semana e mês e baixe o resumo em texto.

O QR Code é renderizado pelo JavaScript QRCode.js via CDN, sem API de pagamentos. Se a conexão com a CDN falhar, o Pix Copia e Cola permanece disponível.

Para testar a estrutura do Pix, execute `vendor/bin/phpunit tests/unit/PixServiceTest.php`.

**Atenção:** gerar o Pix não comprova recebimento; este MVP não acessa o extrato bancário automaticamente. As vendas manuais são registradas separadamente dos pedidos para evitar duplicidade.
