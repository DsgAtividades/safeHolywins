<?php
/**
 * Arquivo de Configurações do Sistema
 * Aqui estão todas as configurações gerais do sistema
 */

// ====================================
// CONFIGURAÇÕES DE CARTÕES
// ====================================

// Define se CPF é obrigatório no cadastro de cartões
// true = CPF obrigatório | false = CPF opcional
define('CARTAO_CPF_OBRIGATORIO', false);

// Define se Nome é obrigatório no cadastro de cartões
// true = Nome obrigatório | false = Nome opcional
define('CARTAO_NOME_OBRIGATORIO', false);

// Valor fixo do custo do cartão
define('CARTAO_CUSTO_FIXO', 0.00);

// ====================================
// CONFIGURAÇÕES GERAIS
// ====================================

// Nome do sistema
define('SISTEMA_NOME', 'Holy Wins');

// Versão do sistema
define('SISTEMA_VERSAO', '1.0.0');

// Fuso horário
date_default_timezone_set('America/Sao_Paulo');

// ====================================
// CONFIGURAÇÕES DE SESSÃO
// ====================================

// Tempo de sessão em segundos (padrão: 3600 = 1 hora)
define('SESSAO_TEMPO', 3600);

// ====================================
// CONFIGURAÇÕES DE VALIDAÇÃO
// ====================================

// Tamanho mínimo do nome
define('NOME_MIN_LENGTH', 3);

// Tamanho do CPF (sem formatação)
define('CPF_LENGTH', 11);

?>

