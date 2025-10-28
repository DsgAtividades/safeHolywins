<?php
require_once 'config/config.php';
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';

verificarLogin();
verificarPermissao('acessar_configuracoes');

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_configuracoes'])) {
    try {
        $config_file = __DIR__ . '/config/config.php';
        
        // Ler o conteúdo atual
        $config_content = file_get_contents($config_file);
        
        // Atualizar valores
        $cpf_obrigatorio = isset($_POST['cpf_obrigatorio']) ? 'true' : 'false';
        $nome_obrigatorio = isset($_POST['nome_obrigatorio']) ? 'true' : 'false';
        $custo_fixo = floatval($_POST['custo_fixo']);
        
        // Substituir valores no arquivo
        $config_content = preg_replace(
            "/define\('CARTAO_CPF_OBRIGATORIO',\s*(true|false)\);/",
            "define('CARTAO_CPF_OBRIGATORIO', $cpf_obrigatorio);",
            $config_content
        );
        
        $config_content = preg_replace(
            "/define\('CARTAO_NOME_OBRIGATORIO',\s*(true|false)\);/",
            "define('CARTAO_NOME_OBRIGATORIO', $nome_obrigatorio);",
            $config_content
        );
        
        $config_content = preg_replace(
            "/define\('CARTAO_CUSTO_FIXO',\s*[\d.]+\);/",
            "define('CARTAO_CUSTO_FIXO', $custo_fixo);",
            $config_content
        );
        
        // Salvar arquivo
        if (file_put_contents($config_file, $config_content)) {
            $mensagem = "Configurações salvas com sucesso! Recarregue a página para aplicar as alterações.";
            $tipo_mensagem = "success";
        } else {
            throw new Exception("Erro ao salvar arquivo de configurações");
        }
        
    } catch (Exception $e) {
        $mensagem = "Erro: " . $e->getMessage();
        $tipo_mensagem = "danger";
    }
}

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-gear-fill"></i> Configurações do Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($mensagem): ?>
                        <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Configurações de Cartões -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="bi bi-credit-card"></i> Configurações de Cartões
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="cpf_obrigatorio" name="cpf_obrigatorio"
                                                   <?php echo CARTAO_CPF_OBRIGATORIO ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="cpf_obrigatorio">
                                                <strong>CPF Obrigatório</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Se desativado, o CPF será gerado automaticamente ao cadastrar cartões
                                                </small>
                                            </label>
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="nome_obrigatorio" name="nome_obrigatorio"
                                                   <?php echo CARTAO_NOME_OBRIGATORIO ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="nome_obrigatorio">
                                                <strong>Nome Obrigatório</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Se desativado, será usado "Cartão [código]" como nome padrão
                                                </small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="custo_fixo" class="form-label">
                                                <strong>Custo Fixo do Cartão</strong>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" 
                                                       id="custo_fixo" name="custo_fixo"
                                                       value="<?php echo CARTAO_CUSTO_FIXO; ?>"
                                                       step="0.01" min="0" required>
                                            </div>
                                            <small class="text-muted">
                                                Valor inicial do saldo ao alocar um novo cartão
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Configurações Atuais:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>CPF: <?php echo CARTAO_CPF_OBRIGATORIO ? '<span class="badge bg-danger">Obrigatório</span>' : '<span class="badge bg-success">Opcional</span>'; ?></li>
                                        <li>Nome: <?php echo CARTAO_NOME_OBRIGATORIO ? '<span class="badge bg-danger">Obrigatório</span>' : '<span class="badge bg-success">Opcional</span>'; ?></li>
                                        <li>Custo do Cartão: <span class="badge bg-primary">R$ <?php echo number_format(CARTAO_CUSTO_FIXO, 2, ',', '.'); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Sistema -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="bi bi-info-circle"></i> Informações do Sistema
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nome do Sistema:</strong> <?php echo SISTEMA_NOME; ?></p>
                                        <p><strong>Versão:</strong> <?php echo SISTEMA_VERSAO; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Fuso Horário:</strong> <?php echo date_default_timezone_get(); ?></p>
                                        <p><strong>Data/Hora Atual:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" name="salvar_configuracoes" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validação do formulário
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include 'includes/footer.php'; ?>

