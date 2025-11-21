# Sistema de E-commerce - PHP & MySQL

Sistema completo de e-commerce desenvolvido em PHP com MySQL, incluindo área administrativa e área do usuário.

## 🚀 Funcionalidades Implementadas

### 1. CRUD de Categorias
- ✅ Tabela `categoria` (codigo, nome)
- ✅ Pasta `admin/categoria` com CRUD completo
- ✅ Criar, editar, listar e excluir categorias
- ✅ Validação de categorias vinculadas a produtos

### 2. Relacionamento Produto-Categoria
- ✅ Chave estrangeira `codigo_categoria` em produto
- ✅ CRUD de produtos vinculado a categorias
- ✅ Seleção de categoria ao criar/editar produto

### 3. Busca por Categoria
- ✅ Filtro de produtos por categoria na área do usuário
- ✅ Interface com botões de filtro
- ✅ Exibição de todos os produtos ou filtrados

### 4. Dono do Produto
- ✅ Chave estrangeira `codigo_dono` em produto
- ✅ Ao criar produto, insere automaticamente o código do usuário logado
- ✅ Usuários podem listar todos os produtos
- ✅ Usuários só podem editar/excluir seus próprios produtos
- ✅ Validação de permissão antes de editar/excluir

### 5. Carrinho de Compras Completo
- ✅ Adicionar produtos ao carrinho
- ✅ Remover produtos do carrinho
- ✅ Exibir preço total do carrinho
- ✅ Finalizar compra (criar pedido)
- ✅ Limpar carrinho após finalização
- ✅ Atualizar estoque após compra
- ✅ Transações seguras com rollback em caso de erro

## 📋 Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache ou Nginx com mod_rewrite
- XAMPP, WAMP, LAMP ou similar

## 🔧 Instalação

### 1. Configurar o Banco de Dados

1. Abra o XAMPP e inicie o MySQL
2. Acesse o phpMyAdmin (http://localhost/phpmyadmin)
3. Importe o arquivo `sql/database.sql` ou execute o script SQL manualmente
4. O banco de dados `sistema_ecommerce` será criado automaticamente

### 2. Configurar a Aplicação

1. Clone ou extraia o projeto na pasta `htdocs` do XAMPP
2. Edite o arquivo `config/database.php` se necessário:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_ecommerce');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### 3. Acessar o Sistema

Acesse: `http://localhost/sistema/` (ajuste o caminho conforme sua instalação)

## 👥 Contas de Teste

O sistema já vem com contas pré-cadastradas:

### Administrador
- **Email:** admin@sistema.com
- **Senha:** admin123
- **Permissões:** Acesso total ao sistema

### Usuários
- **Email:** joao@email.com | **Senha:** admin123
- **Email:** maria@email.com | **Senha:** admin123
- **Permissões:** Comprar produtos e gerenciar seus próprios produtos

## 📁 Estrutura do Projeto

```
sistema/
├── admin/                      # Área administrativa
│   ├── categoria/             # CRUD de categorias
│   │   ├── index.php         # Listar categorias
│   │   ├── criar.php         # Criar categoria
│   │   ├── editar.php        # Editar categoria
│   │   └── excluir.php       # Excluir categoria
│   ├── produto/              # CRUD de produtos (admin)
│   │   ├── index.php         # Listar produtos
│   │   ├── criar.php         # Criar produto
│   │   ├── editar.php        # Editar produto
│   │   └── excluir.php       # Excluir produto
│   ├── conta/                # Gerenciar contas
│   │   └── index.php         # Listar contas
│   └── index.php             # Dashboard admin
├── user/                      # Área do usuário
│   ├── index.php             # Listar produtos (com filtro por categoria)
│   ├── meus_produtos.php     # Produtos do usuário
│   ├── criar_produto.php     # Criar produto (usuário)
│   ├── editar_produto.php    # Editar produto (usuário)
│   ├── excluir_produto.php   # Excluir produto (usuário)
│   ├── carrinho.php          # Visualizar carrinho
│   ├── adicionar_carrinho.php # Adicionar ao carrinho
│   ├── remover_carrinho.php  # Remover do carrinho
│   └── finalizar_compra.php  # Finalizar compra
├── config/                    # Configurações
│   ├── database.php          # Conexão com banco
│   └── session.php           # Gerenciamento de sessão
├── sql/                       # Scripts SQL
│   └── database.sql          # Estrutura do banco
├── index.php                  # Página inicial (redireciona para login)
├── login.php                  # Página de login
├── logout.php                 # Logout
└── README.md                  # Este arquivo
```

## 🗄️ Estrutura do Banco de Dados

### Tabelas Principais

1. **conta** - Usuários do sistema
   - codigo (PK)
   - nome, email, senha
   - tipo (admin/user)

2. **categoria** - Categorias de produtos
   - codigo (PK)
   - nome

3. **produto** - Produtos do sistema
   - codigo (PK)
   - nome, descricao, preco, estoque, imagem
   - codigo_categoria (FK → categoria)
   - codigo_dono (FK → conta)

4. **carrinho** - Itens no carrinho
   - codigo (PK)
   - codigo_conta (FK → conta)
   - codigo_produto (FK → produto)
   - quantidade

5. **pedido** - Pedidos finalizados
   - codigo (PK)
   - codigo_conta (FK → conta)
   - valor_total, status

6. **item_pedido** - Itens dos pedidos
   - codigo (PK)
   - codigo_pedido (FK → pedido)
   - codigo_produto (FK → produto)
   - quantidade, preco_unitario, subtotal

## 🔐 Segurança

- ✅ Senhas criptografadas com `password_hash()`
- ✅ Proteção contra SQL Injection (PDO com prepared statements)
- ✅ Validação de permissões (usuário só edita seus produtos)
- ✅ Sessões seguras
- ✅ Transações de banco de dados para operações críticas

## 🎯 Fluxo de Uso

### Área do Usuário
1. Login no sistema
2. Navegar pelos produtos (com filtro por categoria)
3. Adicionar produtos ao carrinho
4. Visualizar carrinho com total
5. Finalizar compra
6. Gerenciar seus próprios produtos

### Área Administrativa
1. Login como admin
2. Gerenciar categorias
3. Gerenciar todos os produtos
4. Visualizar contas
5. Acessar estatísticas

## 📝 Observações

- O sistema usa transações para garantir integridade dos dados
- Ao finalizar compra, o estoque é atualizado automaticamente
- Produtos sem estoque não podem ser adicionados ao carrinho
- Categorias com produtos vinculados não podem ser excluídas
- Usuários só podem editar/excluir produtos que criaram

## 🐛 Troubleshooting

### Erro de conexão com banco de dados
- Verifique se o MySQL está rodando no XAMPP
- Confirme as credenciais em `config/database.php`
- Certifique-se de que o banco foi criado

### Página em branco
- Ative a exibição de erros no PHP:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

### Sessão não funciona
- Verifique as permissões da pasta de sessões do PHP
- Certifique-se de que `session_start()` é chamado

## 📄 Licença

Este projeto é um sistema de estudo e pode ser usado livremente para fins educacionais.

## 👨‍💻 Desenvolvimento

Sistema desenvolvido com:
- PHP 7.4+
- MySQL 5.7+
- HTML5 + CSS3
- PDO para acesso ao banco de dados
- Arquitetura MVC simplificada
