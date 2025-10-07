CREATE TABLE `estrutura` (
    -- Chave Primária e Identificador Único
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Campos de Texto
    `titulo` VARCHAR(255) NOT NULL,
    `mensagem` TEXT, -- TEXT é bom para mensagens mais longas
    
    -- Campos para Imagem de Miniatura (Thumbnail)
    `capa` LONGBLOB, -- Armazena os dados binários da imagem
    `tipo_capa` VARCHAR(50), -- Armazena o tipo MIME (ex: image/jpeg)
    
    -- Campos para Imagem Principal (Opcional, se for diferente da capa)
    `imagem` LONGBLOB, -- Armazena os dados binários da imagem
    `tipo_imagem` VARCHAR(50), -- Armazena o tipo MIME
    
    -- Campos para Arquivos (o que foi discutido anteriormente: nome e caminho/dados)
    `arquivo` VARCHAR(512), -- Para armazenar o caminho/URL do arquivo (abordagem recomendada)
    -- Se você quiser armazenar o arquivo binário: `arquivo_binario` LONGBLOB,
    `nome_arquivo` VARCHAR(255), -- Nome original do arquivo para download
    `tipo_arquivo` VARCHAR(50), -- Armazena o tipo MIME do arquivo (ex: application/pdf)
    
    -- Metadados
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);