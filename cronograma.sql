-- Criação da Tabela 'cronograma'
CREATE TABLE cronograma (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT,
    data_evento DATE NOT NULL,
    hora_evento TIME,
    
    imagem LONGBLOB, 
    tipo_imagem VARCHAR(100), 
    
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);