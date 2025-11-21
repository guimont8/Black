# 📦 Guia de Instalação - Sistema E-commerce

## 🎯 Pré-requisitos

- XAMPP (ou WAMP/LAMP)
- Git instalado
- Navegador web moderno

## 🚀 Instalação Passo a Passo

### 1. Baixar o Projeto

Você pode baixar o projeto de duas formas:

#### Opção A: Clonar do GitHub (Recomendado)
```bash
cd C:\xampp\htdocs
git clone [URL_DO_SEU_REPOSITORIO] sistema-ecommerce
cd sistema-ecommerce
```

#### Opção B: Download Manual
1. Baixe o arquivo ZIP do projeto
2. Extraia na pasta `C:\xampp\htdocs\sistema-ecommerce`

### 2. Configurar o Banco de Dados

1. **Inicie o XAMPP**
   - Abra o XAMPP Control Panel
   - Inicie o Apache
   - Inicie o MySQL

2. **Criar o Banco de Dados**
   - Acesse: http://localhost/phpmyadmin
   - Clique em "Novo" na barra lateral
   - Nome do banco: `sistema_ecommerce`
   - Collation: `utf8mb4_general_ci`
   - Clique em "Criar"

3. **Importar a Estrutura**
   - Selecione o banco `sistema_ecommerce`
   - Clique na aba "Importar"
   - Clique em "Escolher arquivo"
   - Selecione o arquivo `sql/database.sql`
   - Clique em "Executar"

### 3. Configurar a Aplicação

1. **Verificar Configurações do Banco**
   
   Abra o arquivo `config/database.php` e verifique:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_ecommerce');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Deixe vazio se não tiver senha
   ```

2. **Configurar Permissões** (Linux/Mac)
   ```bash
   chmod -R 755 /caminho/para/sistema-ecommerce
   ```

### 4. Acessar o Sistema

1. Abra seu navegador
2. Acesse: http://localhost/sistema-ecommerce
3. Você será redirecionado para a página de login

### 5. Fazer Login

Use uma das contas pré-cadastradas:

**Administrador:**
- Email: `admin@sistema.com`
- Senha: `admin123`

**Usuários:**
- Email: `joao@email.com` | Senha: `admin123`
- Email: `maria@email.com` | Senha: `admin123`

## 🔧 Solução de Problemas

### Erro: "Erro na conexão com o banco de dados"

**Solução:**
1. Verifique se o MySQL está rodando no XAMPP
2. Confirme que o banco `sistema_ecommerce` foi criado
3. Verifique as credenciais em `config/database.php`
4. Teste a conexão no phpMyAdmin

### Erro: "Página em branco"

**Solução:**
1. Ative a exibição de erros no PHP
2. Edite o arquivo `php.ini` do XAMPP:
   ```ini
   display_errors = On
   error_reporting = E_ALL
   ```
3. Reinicie o Apache no XAMPP

### Erro: "Session não funciona"

**Solução:**
1. Verifique se a pasta de sessões existe:
   - Windows: `C:\xampp\tmp`
   - Linux: `/tmp`
2. Verifique permissões de escrita na pasta

### Erro 404 - Página não encontrada

**Solução:**
1. Verifique se o mod_rewrite está ativado no Apache
2. Edite `httpd.conf` e descomente:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Reinicie o Apache

### Erro: "Access denied for user"

**Solução:**
1. Verifique o usuário e senha do MySQL
2. No XAMPP, o padrão é:
   - Usuário: `root`
   - Senha: (vazio)
3. Se você definiu uma senha, atualize em `config/database.php`

## 📱 Testando o Sistema

### Como Administrador:
1. Login com `admin@sistema.com`
2. Acesse "Categorias" e crie algumas categorias
3. Acesse "Produtos" e cadastre produtos
4. Visualize as estatísticas no Dashboard

### Como Usuário:
1. Login com `joao@email.com`
2. Navegue pelos produtos
3. Use os filtros de categoria
4. Adicione produtos ao carrinho
5. Finalize uma compra
6. Acesse "Meus Produtos" para criar seus próprios produtos

## 🌐 Configuração para Produção

### Segurança:

1. **Alterar senhas padrão:**
   ```sql
   UPDATE conta SET senha = '$2y$10$...' WHERE email = 'admin@sistema.com';
   ```

2. **Desabilitar exibição de erros:**
   ```php
   // Em config/database.php
   ini_set('display_errors', 0);
   error_reporting(0);
   ```

3. **Configurar HTTPS:**
   - Obtenha um certificado SSL
   - Configure o Apache para usar HTTPS
   - Force redirecionamento HTTP → HTTPS

4. **Backup do Banco:**
   ```bash
   mysqldump -u root -p sistema_ecommerce > backup.sql
   ```

## 📊 Estrutura de Pastas

```
sistema-ecommerce/
├── admin/              # Área administrativa
│   ├── categoria/     # CRUD categorias
│   ├── produto/       # CRUD produtos
│   └── conta/         # Gerenciar contas
├── user/              # Área do usuário
├── config/            # Configurações
├── sql/               # Scripts SQL
├── .htaccess          # Configuração Apache
├── index.php          # Página inicial
├── login.php          # Login
└── README.md          # Documentação
```

## 🆘 Suporte

Se você encontrar problemas:

1. Verifique os logs de erro do Apache:
   - Windows: `C:\xampp\apache\logs\error.log`
   - Linux: `/var/log/apache2/error.log`

2. Verifique os logs do MySQL:
   - Windows: `C:\xampp\mysql\data\mysql_error.log`

3. Ative o modo debug no PHP para ver erros detalhados

## ✅ Checklist de Instalação

- [ ] XAMPP instalado e funcionando
- [ ] Apache iniciado
- [ ] MySQL iniciado
- [ ] Banco de dados criado
- [ ] Arquivo SQL importado
- [ ] Configurações verificadas
- [ ] Sistema acessível no navegador
- [ ] Login funcionando
- [ ] Produtos sendo exibidos

## 🎓 Próximos Passos

Após a instalação bem-sucedida:

1. Explore a área administrativa
2. Crie categorias personalizadas
3. Cadastre produtos
4. Teste o fluxo de compra
5. Experimente criar produtos como usuário
6. Teste as permissões de edição

---

**Desenvolvido para fins educacionais**
