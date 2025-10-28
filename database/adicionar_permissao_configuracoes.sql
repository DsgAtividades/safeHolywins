-- ====================================================================
-- Script para adicionar permissão de Configurações do Sistema
-- ====================================================================

-- 1. Criar a permissão
INSERT INTO permissoes (nome, pagina) 
VALUES ('acessar_configuracoes', 'configuracoes_sistema.php')
ON DUPLICATE KEY UPDATE pagina = 'configuracoes_sistema.php';

-- 2. Obter o ID da permissão recém-criada
SET @permissao_id = (SELECT id FROM permissoes WHERE nome = 'acessar_configuracoes' LIMIT 1);

-- 3. Obter o ID do grupo Administrador
SET @grupo_admin_id = (SELECT id FROM grupos WHERE nome LIKE '%dministrador%' ORDER BY id LIMIT 1);

-- 4. Adicionar a permissão ao grupo Administrador (se não existir)
-- Tenta com 'grupo_permissoes' (ignora erro se não existir)
INSERT IGNORE INTO grupo_permissoes (grupo_id, permissao_id)
VALUES (@grupo_admin_id, @permissao_id);

-- Se a tabela acima não existir, tenta com 'grupos_permissoes'
-- Execute apenas UMA das duas linhas acima, dependendo do nome da sua tabela

-- Alternativa para grupos_permissoes (comente a linha acima e descomente esta):
-- INSERT IGNORE INTO grupos_permissoes (grupo_id, permissao_id)
-- VALUES (@grupo_admin_id, @permissao_id);

-- 5. Verificar o resultado
-- Use o nome da tabela correto (grupo_permissoes OU grupos_permissoes)
SELECT 
    p.id as permissao_id,
    p.nome as permissao_nome,
    p.pagina as permissao_pagina,
    g.id as grupo_id,
    g.nome as grupo_nome
FROM permissoes p
LEFT JOIN grupo_permissoes gp ON p.id = gp.permissao_id
LEFT JOIN grupos g ON gp.grupo_id = g.id
WHERE p.nome = 'acessar_configuracoes';

-- Se o SELECT acima der erro, tente este:
-- SELECT 
--     p.id as permissao_id,
--     p.nome as permissao_nome,
--     p.pagina as permissao_pagina,
--     g.id as grupo_id,
--     g.nome as grupo_nome
-- FROM permissoes p
-- LEFT JOIN grupos_permissoes gp ON p.id = gp.permissao_id
-- LEFT JOIN grupos g ON gp.grupo_id = g.id
-- WHERE p.nome = 'acessar_configuracoes';

-- ====================================================================
-- INSTRUÇÕES:
-- 1. Execute este script no seu banco de dados MySQL
-- 2. Faça logout e login novamente no sistema
-- 3. Acesse: configuracoes_sistema.php
-- ====================================================================

