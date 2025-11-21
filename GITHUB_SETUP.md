# 🚀 Como Publicar no GitHub

Este guia mostra como publicar este projeto no GitHub.

## 📋 Pré-requisitos

- Conta no GitHub (crie em https://github.com se não tiver)
- Git instalado no seu computador

## 🔧 Método 1: Usando a Interface Web do GitHub (Mais Fácil)

### Passo 1: Criar Repositório no GitHub

1. Acesse https://github.com
2. Faça login na sua conta
3. Clique no botão **"+"** no canto superior direito
4. Selecione **"New repository"**
5. Preencha:
   - **Repository name:** `sistema-ecommerce-php`
   - **Description:** `Sistema completo de e-commerce em PHP com MySQL`
   - **Visibility:** Public (ou Private se preferir)
   - **NÃO** marque "Initialize this repository with a README"
6. Clique em **"Create repository"**

### Passo 2: Conectar seu Projeto Local ao GitHub

Abra o terminal/prompt de comando na pasta do projeto e execute:

```bash
# Navegar até a pasta do projeto
cd /caminho/para/sistema-ecommerce

# Adicionar o repositório remoto (substitua SEU_USUARIO pelo seu nome de usuário do GitHub)
git remote add origin https://github.com/SEU_USUARIO/sistema-ecommerce-php.git

# Enviar o código para o GitHub
git push -u origin main
```

### Passo 3: Autenticar

Quando solicitado, você precisará autenticar:

**Opção A: Token de Acesso Pessoal (Recomendado)**
1. Acesse: https://github.com/settings/tokens
2. Clique em "Generate new token" → "Generate new token (classic)"
3. Dê um nome: `Sistema E-commerce`
4. Marque o escopo: `repo` (acesso completo aos repositórios)
5. Clique em "Generate token"
6. **COPIE O TOKEN** (você não verá novamente!)
7. Use o token como senha quando o Git solicitar

**Opção B: GitHub CLI**
```bash
# Instalar GitHub CLI (se não tiver)
# Windows: baixe de https://cli.github.com
# Mac: brew install gh
# Linux: sudo apt install gh

# Autenticar
gh auth login

# Seguir as instruções na tela
```

## 🔧 Método 2: Usando GitHub Desktop (Interface Gráfica)

### Passo 1: Instalar GitHub Desktop

1. Baixe em: https://desktop.github.com
2. Instale e faça login com sua conta GitHub

### Passo 2: Adicionar o Repositório

1. Abra GitHub Desktop
2. Clique em **"File"** → **"Add Local Repository"**
3. Selecione a pasta do projeto
4. Clique em **"Add Repository"**

### Passo 3: Publicar

1. Clique em **"Publish repository"**
2. Preencha:
   - **Name:** `sistema-ecommerce-php`
   - **Description:** `Sistema completo de e-commerce em PHP com MySQL`
   - Desmarque "Keep this code private" se quiser público
3. Clique em **"Publish Repository"**

## 📝 Comandos Git Úteis

### Verificar Status
```bash
git status
```

### Adicionar Novos Arquivos
```bash
git add .
```

### Fazer Commit
```bash
git commit -m "Descrição das mudanças"
```

### Enviar para GitHub
```bash
git push
```

### Atualizar do GitHub
```bash
git pull
```

### Ver Histórico
```bash
git log --oneline
```

## 🔄 Atualizando o Projeto no GitHub

Sempre que fizer mudanças no código:

```bash
# 1. Adicionar arquivos modificados
git add .

# 2. Fazer commit com mensagem descritiva
git commit -m "Descrição do que foi alterado"

# 3. Enviar para o GitHub
git push
```

## 🌿 Trabalhando com Branches

### Criar uma Nova Branch
```bash
git checkout -b nome-da-feature
```

### Listar Branches
```bash
git branch
```

### Mudar de Branch
```bash
git checkout main
```

### Fazer Merge
```bash
git checkout main
git merge nome-da-feature
```

## 📦 Estrutura do Repositório

Seu repositório no GitHub terá:

```
sistema-ecommerce-php/
├── README.md              # Documentação principal
├── INSTALACAO.md          # Guia de instalação
├── GITHUB_SETUP.md        # Este arquivo
├── .htaccess              # Configuração Apache
├── admin/                 # Área administrativa
├── user/                  # Área do usuário
├── config/                # Configurações
├── sql/                   # Scripts SQL
└── ...
```

## 🔐 Segurança

### ⚠️ IMPORTANTE: Não Commitar Informações Sensíveis

Antes de publicar, certifique-se de:

1. **Não incluir senhas reais** no código
2. **Não incluir chaves de API** ou tokens
3. **Usar variáveis de ambiente** para dados sensíveis

### Criar .gitignore

Crie um arquivo `.gitignore` na raiz do projeto:

```gitignore
# Configurações locais
config/local.php

# Arquivos de sistema
.DS_Store
Thumbs.db

# IDEs
.vscode/
.idea/
*.swp
*.swo

# Logs
*.log

# Uploads (se houver)
uploads/
```

## 📖 Melhorando o README

Adicione ao seu README.md:

- Badge do GitHub
- Screenshots do sistema
- Demonstração online (se houver)
- Contribuidores
- Licença

Exemplo de badges:
```markdown
![PHP](https://img.shields.io/badge/PHP-7.4+-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![License](https://img.shields.io/badge/license-MIT-green)
```

## 🤝 Colaboração

### Permitir Contribuições

1. Adicione um arquivo `CONTRIBUTING.md`
2. Configure as Issues no GitHub
3. Use Pull Requests para revisão de código

### Adicionar Colaboradores

1. Vá para o repositório no GitHub
2. Clique em **"Settings"**
3. Clique em **"Collaborators"**
4. Adicione usuários pelo nome ou email

## 📊 GitHub Pages (Opcional)

Se quiser hospedar a documentação:

1. Vá em **"Settings"** → **"Pages"**
2. Selecione a branch `main`
3. Selecione a pasta `/docs` ou `/root`
4. Clique em **"Save"**

## 🆘 Problemas Comuns

### "Permission denied"
- Verifique suas credenciais
- Use token de acesso pessoal em vez de senha

### "Repository not found"
- Verifique se o nome do repositório está correto
- Verifique se você tem permissão de acesso

### "Failed to push"
- Faça `git pull` primeiro para sincronizar
- Resolva conflitos se houver
- Tente `git push` novamente

### "Large files"
- GitHub tem limite de 100MB por arquivo
- Use Git LFS para arquivos grandes
- Ou remova arquivos grandes do histórico

## 📱 Compartilhando seu Projeto

Após publicar, compartilhe:

```
🚀 Meu Sistema E-commerce em PHP!

📦 Repositório: https://github.com/SEU_USUARIO/sistema-ecommerce-php
⭐ Dê uma estrela se gostar!

Funcionalidades:
✅ CRUD completo
✅ Carrinho de compras
✅ Sistema de categorias
✅ Permissões de usuário
✅ Finalização de compra

#PHP #MySQL #WebDevelopment #Ecommerce
```

## ✅ Checklist de Publicação

- [ ] Repositório criado no GitHub
- [ ] Código enviado com `git push`
- [ ] README.md atualizado
- [ ] .gitignore configurado
- [ ] Sem informações sensíveis no código
- [ ] Documentação completa
- [ ] Licença adicionada (se aplicável)

## 🎓 Recursos Adicionais

- [Documentação Git](https://git-scm.com/doc)
- [GitHub Guides](https://guides.github.com)
- [Git Cheat Sheet](https://education.github.com/git-cheat-sheet-education.pdf)

---

**Boa sorte com seu projeto! 🚀**
