-- =====================================================
-- BANCO DE DADOS: LOJA DE VESTUÁRIO
-- SGBD: MySQL
-- =====================================================


-- 1. CRIAÇÃO DO BANCO DE DADOS
CREATE DATABASE lojavestuario;

-- Seleciona o banco
USE lojavestuario;


-- =====================================================
-- 2. TABELA CLIENTE
-- =====================================================

CREATE TABLE cliente (
    id INT AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20),
    dataCadastro DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_cliente
        PRIMARY KEY (id),

    CONSTRAINT uk_cliente_cpf
        UNIQUE (cpf),

    CONSTRAINT uk_cliente_email
        UNIQUE (email)
);


-- =====================================================
-- 3. TABELA PRODUTO
-- =====================================================

CREATE TABLE produto (
    id INT AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    descricao VARCHAR(500),
    preco DECIMAL(10,2) NOT NULL,
    quantidadeEstoque INT NOT NULL DEFAULT 0,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    dataCadastro DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_produto
        PRIMARY KEY (id)
);


-- =====================================================
-- 4. TABELA PEDIDO
-- =====================================================

CREATE TABLE pedido (
    id INT AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    dataPedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(30) NOT NULL,
    valorTotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    CONSTRAINT pk_pedido
        PRIMARY KEY (id),

    CONSTRAINT fk_pedido_cliente
        FOREIGN KEY (cliente_id)
        REFERENCES cliente(id)
);


-- =====================================================
-- 5. TABELA ASSOCIATIVA PEDIDO_PRODUTO
-- =====================================================

CREATE TABLE pedido_produto (
    id INT AUTO_INCREMENT,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    precoUnitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    CONSTRAINT pk_pedido_produto
        PRIMARY KEY (id),

    CONSTRAINT fk_pedido_produto_pedido
        FOREIGN KEY (pedido_id)
        REFERENCES pedido(id),

    CONSTRAINT fk_pedido_produto_produto
        FOREIGN KEY (produto_id)
        REFERENCES produto(id)
);