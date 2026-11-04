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

Na **pasta principal do projeto**, onde estão o `README.md`, o `composer.json` e o arquivo `spark`, crie um arquivo chamado **`.env`**.

No Windows com XAMPP, o caminho será semelhante a:

```text
C:\xampp\htdocs\crochettei\.env
```

> O arquivo `env` usado como modelo em alguns projetos CodeIgniter não está incluído neste repositório. Por isso, você pode criar o `.env` diretamente. Se já tiver um `.env` configurado, apenas edite o existente. **Não crie `.env.txt`** nem coloque o arquivo em `app/` ou `public/`.

Adicione as configurações abaixo dentro do arquivo:

```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = crochettei
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

# Telefone da equipe de suporte no WhatsApp
CROCHETTEI_SUPORTE_WHATSAPP = 5527999991234
```

**Importante:** o telefone acima é fictício e serve apenas para mostrar o formato. Substitua-o pelo **número real da equipe**, com `55` (Brasil) + DDD + telefone, sem parênteses, espaços, hífens ou sinal de `+`. Por exemplo: `55` + `27` + `999991234`.

Se a configuração de banco já estiver pronta, basta **acrescentar a linha `CROCHETTEI_SUPORTE_WHATSAPP` ao final do seu `.env`**, sem apagar as outras configurações. Veja mais detalhes na seção [Configurar o suporte](#configurar-o-suporte).

> Deixe `database.default.password` em branco se o seu MySQL local não tiver senha (padrão do XAMPP). O `.env` é local e já está listado no `.gitignore`, portanto não deve ser enviado ao GitHub.

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


## PBIs 04 e 12

**PBI-04 — Alerta preventivo do Pix:** nos pedidos Pix pendentes, a artesã recebe uma orientação em destaque para conferir o recebimento diretamente no aplicativo bancário. Antes de confirmar o pagamento, precisa marcar a caixa de verificação. O servidor também valida esse campo. O mesmo cuidado foi aplicado ao administrador.

**PBI-12 — Suporte via WhatsApp:** a artesã tem um botão "Preciso de Ajuda" no painel e um atalho em Configurações da Minha Conta. Ambos usam o contato configurado e abrem uma conversa com mensagem pronta.

### Configurar o suporte

O número usado pelo botão **Preciso de Ajuda** é lido da variável `CROCHETTEI_SUPORTE_WHATSAPP`, no arquivo **`.env` da raiz do projeto**. Não é preciso alterar os arquivos PHP ou HTML.

1. Abra a pasta do Crochettei no seu computador (a mesma que contém o `composer.json` e o `spark`).
2. Localize o arquivo `.env`. Se ele ainda não existir, crie-o conforme a [etapa 3 da instalação](#3-configurar-o-ambiente).
3. **No final do arquivo `.env`**, em uma nova linha, insira a configuração abaixo, substituindo o telefone fictício pelo número oficial da equipe:

   ```env
   CROCHETTEI_SUPORTE_WHATSAPP = 5527999991234
   ```

4. Salve o arquivo. Se estiver usando `php spark serve`, reinicie o servidor para garantir que a alteração seja carregada.
5. Entre com uma conta de artesã e clique em **Preciso de Ajuda**, no painel, ou acesse **Configurações da Minha Conta > Precisa de Ajuda?**. Confira se o WhatsApp abre para o número correto com a mensagem preenchida.

**Formato obrigatório:** código do Brasil (`55`) + DDD (2 dígitos) + telefone (8 ou 9 dígitos), **somente números**. Não utilize `+`, espaços, traços ou parênteses. O exemplo `5527999991234` não é um contato real de suporte.

Se o `.env` já tiver configurações de banco, **não as substitua**: adicione apenas a variável do WhatsApp. Se não houver número configurado ou ele for inválido, o sistema exibirá o suporte como indisponível em vez de usar um número fictício.

> O `.env` contém configurações locais e não deve ser publicado no GitHub.

### Como conferir

1. Faça login como artesã e entre em **Meus Pedidos**, com um Pix pendente.
2. Confira o alerta de segurança e o bloqueio do botão de confirmação.
3. Marque que conferiu o extrato do banco: o botão deve ser liberado.
4. Tente enviar uma confirmação sem o campo marcado: o servidor deve rejeitá-la.
5. Entre no painel e teste o botão **Preciso de Ajuda** com o número real configurado.
6. Execute `vendor/bin/phpunit tests/unit/SuporteWhatsAppServiceTest.php`.

Esses dois PBIs não exigem nova migration.

