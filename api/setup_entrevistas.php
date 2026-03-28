<?php
require 'config.php';

// Tabela consolidada para a Entrevista Diagnóstica Completa (20+ campos)
$sql = "CREATE TABLE IF NOT EXISTS entrevistas_completas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    empresa_nome VARCHAR(255),
    respondente_nome VARCHAR(255),
    respondente_cargo VARCHAR(255),
    data_entrevista DATE,
    porte_empresa VARCHAR(50),
    regiao_atuacao VARCHAR(255),
    
    -- Seção 2: Contexto Financeiro
    q1_beneficiarios VARCHAR(100),
    q2_custo_mensal VARCHAR(100),
    q3_crescimento VARCHAR(100),
    
    -- Seção 3: Processos
    q4_protocolo VARCHAR(100),
    q5_equipe_dedicada VARCHAR(100),
    q6_ferramenta_acompanhamento TEXT,
    q7_auditoria VARCHAR(100),
    
    -- Seção 7: Matriz de Entraves (Gravidade)
    g1 INT DEFAULT 0, g2 INT DEFAULT 0, g3 INT DEFAULT 0, g4 INT DEFAULT 0, 
    g5 INT DEFAULT 0, g6 INT DEFAULT 0, g7 INT DEFAULT 0, g8 INT DEFAULT 0, 
    g9 INT DEFAULT 0, g10 INT DEFAULT 0, g11 INT DEFAULT 0,

    -- Seção 7: Matriz de Entraves (Frequência)
    f1 VARCHAR(50), f2 VARCHAR(50), f3 VARCHAR(50), f4 VARCHAR(50), 
    f5 VARCHAR(50), f6 VARCHAR(50), f7 VARCHAR(50), f8 VARCHAR(50), 
    f9 VARCHAR(50), f10 VARCHAR(50), f11 VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela 'entrevistas_completas' configurada com sucesso!";
} else {
    echo "Erro ao criar tabela: " . $conn->error;
}
?>
