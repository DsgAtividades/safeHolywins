-- Script para adicionar a coluna primeiro_login na tabela usuarios
-- Execute este script no seu banco de dados se quiser manter a funcionalidade de primeiro login

ALTER TABLE `usuarios` 
ADD COLUMN `primeiro_login` tinyint(1) DEFAULT 1 AFTER `ativo`;

-- Atualizar usuários existentes para não forçar primeiro login
UPDATE `usuarios` SET `primeiro_login` = 0 WHERE `primeiro_login` IS NULL OR `primeiro_login` = 1;

