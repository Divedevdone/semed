-- Criação da Tabela 'observacao'
CREATE TABLE observacao (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    observacao TEXT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);