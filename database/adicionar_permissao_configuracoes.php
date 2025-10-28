<?php
/**
 * Script para adicionar a permissão de acessar configurações
 * Executa uma única vez para criar a permissão no banco de dados
 */

require_once __DIR__ . '/../includes/conexao.php';

try {
    echo "<h2>Adicionando Permissão: Acessar Configurações</h2>";
    
    // Verificar qual nome de tabela está sendo usado (grupo_permissoes ou grupos_permissoes)
    $tabela_relacao = 'grupo_permissoes';
    $stmt = $pdo->query("SHOW TABLES LIKE 'grupos_permissoes'");
    if ($stmt->rowCount() > 0) {
        $tabela_relacao = 'grupos_permissoes';
    }
    echo "<p style='color: blue;'>ℹ️ Usando tabela: <strong>$tabela_relacao</strong></p>";
    
    // Verificar se a permissão já existe
    $stmt = $pdo->prepare("SELECT id FROM permissoes WHERE nome = ?");
    $stmt->execute(['acessar_configuracoes']);
    $existe = $stmt->fetch();
    
    if ($existe) {
        echo "<p style='color: orange;'>⚠️ A permissão 'acessar_configuracoes' já existe!</p>";
        $permissao_id = $existe['id'];
    } else {
        // Inserir a nova permissão
        $stmt = $pdo->prepare("
            INSERT INTO permissoes (nome, pagina) 
            VALUES (?, ?)
        ");
        
        $stmt->execute([
            'acessar_configuracoes',
            'configuracoes_sistema.php'
        ]);
        
        $permissao_id = $pdo->lastInsertId();
        echo "<p style='color: green;'>✅ Permissão 'acessar_configuracoes' criada com sucesso! (ID: $permissao_id)</p>";
    }
    
    // Verificar qual é o grupo Administrador
    $stmt = $pdo->prepare("SELECT id FROM grupos WHERE nome LIKE '%dministrador%' ORDER BY id LIMIT 1");
    $stmt->execute();
    $grupo_admin = $stmt->fetch();
    
    if ($grupo_admin) {
        $grupo_id = $grupo_admin['id'];
        
        // Verificar se o grupo já tem essa permissão
        $stmt = $pdo->prepare("
            SELECT * FROM $tabela_relacao 
            WHERE grupo_id = ? AND permissao_id = ?
        ");
        $stmt->execute([$grupo_id, $permissao_id]);
        $vinculo_existe = $stmt->fetch();
        
        if ($vinculo_existe) {
            echo "<p style='color: orange;'>⚠️ O grupo Administrador já possui essa permissão!</p>";
        } else {
            // Adicionar permissão ao grupo Administrador
            $stmt = $pdo->prepare("
                INSERT INTO $tabela_relacao (grupo_id, permissao_id) 
                VALUES (?, ?)
            ");
            $stmt->execute([$grupo_id, $permissao_id]);
            
            echo "<p style='color: green;'>✅ Permissão adicionada ao grupo Administrador!</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Grupo Administrador não encontrado!</p>";
        echo "<p>Você precisará adicionar manualmente a permissão ao grupo desejado.</p>";
    }
    
    // Mostrar informações
    echo "<hr>";
    echo "<h3>Informações:</h3>";
    echo "<ul>";
    echo "<li><strong>Nome da Permissão:</strong> acessar_configuracoes</li>";
    echo "<li><strong>Página:</strong> configuracoes_sistema.php</li>";
    echo "<li><strong>ID da Permissão:</strong> $permissao_id</li>";
    if (isset($grupo_id)) {
        echo "<li><strong>Grupo Administrador ID:</strong> $grupo_id</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3>Próximos Passos:</h3>";
    echo "<ol>";
    echo "<li>✅ Permissão criada no banco de dados</li>";
    echo "<li>✅ Permissão adicionada ao grupo Administrador</li>";
    echo "<li>✅ Menu atualizado em header.php</li>";
    echo "<li>🎯 <strong>Faça logout e login novamente</strong> para atualizar as permissões</li>";
    echo "<li>🎯 Acesse: <a href='../configuracoes_sistema.php'>configuracoes_sistema.php</a></li>";
    echo "</ol>";
    
    echo "<hr>";
    echo "<p><a href='../index.php' class='btn btn-primary'>← Voltar para o Sistema</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Permissão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
</html>

