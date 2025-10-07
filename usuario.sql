CREATE TABLE `usuarios` (
    -- Chave Primária e Identificador Único
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Nome de Usuário (Username)
    `usuario` VARCHAR(100) NOT NULL UNIQUE, -- Único para garantir que não haja duplicatas
    
    -- Senha (Onde o hash será armazenado)
    `senha` VARCHAR(255) NOT NULL, -- O tamanho 255 é o padrão recomendado para o hash de password_hash()
    
    -- Informações Adicionais (Opcional, mas útil)
    `nome_completo` VARCHAR(255),
    `email` VARCHAR(255) UNIQUE,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);