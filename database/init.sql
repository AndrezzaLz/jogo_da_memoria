CREATE DATABASE IF NOT EXISTS yash_db;

USE yash_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS partidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    dimensoes VARCHAR(10) NOT NULL,
    modalidade VARCHAR(20) NOT NULL,
    tempo VARCHAR(10) NOT NULL,
    jogadas INT NOT NULL,
    resultado VARCHAR(20) NOT NULL,
    data_partida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Ja insere um usuario para teste.
INSERT INTO usuarios (nome_completo, data_nascimento, cpf, telefone, email, usuario, senha) 
VALUES 
('Administrador', '2000-01-01', '000.000.000-00', '(11) 99999-9999', 'admin@yash.com', 'admin', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa');