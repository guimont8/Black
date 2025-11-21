-- ============================================
-- Sistema de E-commerce - Database Structure
-- ============================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS sistema_ecommerce;
USE sistema_ecommerce;

-- ============================================
-- Tabela: conta (Usuários)
-- ============================================
CREATE TABLE IF NOT EXISTS conta (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'user') DEFAULT 'user',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabela: categoria
-- ============================================
CREATE TABLE IF NOT EXISTS categoria (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabela: produto
-- ============================================
CREATE TABLE IF NOT EXISTS produto (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT DEFAULT 0,
    imagem VARCHAR(255),
    codigo_categoria INT NOT NULL,
    codigo_dono INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (codigo_categoria) REFERENCES categoria(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (codigo_dono) REFERENCES conta(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabela: carrinho
-- ============================================
CREATE TABLE IF NOT EXISTS carrinho (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    codigo_conta INT NOT NULL,
    codigo_produto INT NOT NULL,
    quantidade INT DEFAULT 1,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (codigo_conta) REFERENCES conta(codigo) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (codigo_produto) REFERENCES produto(codigo) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_carrinho (codigo_conta, codigo_produto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabela: pedido
-- ============================================
CREATE TABLE IF NOT EXISTS pedido (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    codigo_conta INT NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    status ENUM('pendente', 'finalizado', 'cancelado') DEFAULT 'finalizado',
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (codigo_conta) REFERENCES conta(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabela: item_pedido
-- ============================================
CREATE TABLE IF NOT EXISTS item_pedido (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    codigo_pedido INT NOT NULL,
    codigo_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (codigo_pedido) REFERENCES pedido(codigo) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (codigo_produto) REFERENCES produto(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Dados iniciais
-- ============================================

-- Inserir usuário admin padrão (senha: admin123)
INSERT INTO conta (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Maria Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Inserir categorias iniciais
INSERT INTO categoria (nome) VALUES 
('Eletrônicos'),
('Roupas'),
('Livros'),
('Alimentos'),
('Móveis'),
('Esportes');

-- Inserir produtos de exemplo
INSERT INTO produto (nome, descricao, preco, estoque, codigo_categoria, codigo_dono) VALUES 
('Notebook Dell', 'Notebook Dell Inspiron 15, 8GB RAM, 256GB SSD', 2999.90, 10, 1, 1),
('Mouse Logitech', 'Mouse sem fio Logitech M170', 49.90, 50, 1, 1),
('Camiseta Básica', 'Camiseta 100% algodão', 39.90, 100, 2, 2),
('Calça Jeans', 'Calça jeans masculina', 89.90, 30, 2, 2),
('Clean Code', 'Livro sobre código limpo', 79.90, 20, 3, 1),
('Arroz Integral 1kg', 'Arroz integral orgânico', 12.90, 200, 4, 3);

-- ============================================
-- Índices para melhor performance
-- ============================================
CREATE INDEX idx_produto_categoria ON produto(codigo_categoria);
CREATE INDEX idx_produto_dono ON produto(codigo_dono);
CREATE INDEX idx_carrinho_conta ON carrinho(codigo_conta);
CREATE INDEX idx_pedido_conta ON pedido(codigo_conta);
CREATE INDEX idx_item_pedido ON item_pedido(codigo_pedido);
