<?php
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';

//$message = '';
//$error = '';
verificarPermissao('produtos_editar');

// Buscar categorias para o select
$stmt = $pdo->query("SELECT id, nome, icone FROM categorias ORDER BY ordem, nome");
$categorias = $stmt->fetchAll();

// Verificar se foi passado um ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: produtos.php');
    exit;
}

// Buscar dados do produto
$stmt = $pdo->prepare("
    SELECT p.*, c.nome as nome_categoria, c.icone as categoria_icone 
    FROM produtos p 
    LEFT JOIN categorias c ON p.categoria_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar dados
        $nome = trim($_POST['nome'] ?? '');
        $preco = str_replace(',', '.', $_POST['preco'] ?? '0');
        $preco_custo = !empty($_POST['preco_custo']) ? str_replace(',', '.', $_POST['preco_custo']) : null;
        $estoque = (int)($_POST['estoque'] ?? 0);
        $categoria_id = (int)($_POST['categoria_id'] ?? 0);
        
        if (empty($nome)) {
            throw new Exception('O nome do produto é obrigatório');
        }
        
        if (!is_numeric($preco) || $preco <= 0) {
            throw new Exception('Preço inválido');
        }
        
        if ($estoque < 0) {
            throw new Exception('Quantidade inválida');
        }
        
        if ($categoria_id <= 0) {
            throw new Exception('Selecione uma categoria');
        }
        
        // Atualizar produto
        $stmt = $pdo->prepare("UPDATE produtos SET nome_produto = ?, categoria_id = ?, preco_custo = ?, preco = ?, estoque = ? WHERE id = ?");
        $stmt->execute([$nome, $categoria_id, $preco_custo, $preco, $estoque, $id]);
        
        header('Location: produtos.php?success=updated');
        exit;
        
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

include 'includes/header.php';
?>

<div class="container py-4">
    <!-- Título da Página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pencil-square"></i> Editar Produto
                    </h6>
                    <a href="produtos.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Produto atualizado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" id="nome" name="nome" required 
                                       value="<?= htmlspecialchars($produto['nome_produto']) ?>">
                                <div class="invalid-feedback">
                                    Por favor, informe o nome do produto.
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="categoria_id" class="form-label">Categoria</label>
                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['id'] ?>" 
                                            <?= $categoria['id'] == $produto['categoria_id'] ? 'selected' : '' ?>>
                                            <?php if ($categoria['icone']): ?>
                                                <i class="bi bi-<?= htmlspecialchars($categoria['icone']) ?>"></i>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($categoria['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor, selecione uma categoria.
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="preco_custo" class="form-label">Preço de Custo <small class="text-muted">(opcional)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" id="preco_custo" name="preco_custo"
                                           value="<?= $produto['preco_custo'] ? number_format($produto['preco_custo'], 2, ',', '') : '0,00' ?>">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="preco" class="form-label">Preço de Venda</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" id="preco" name="preco" required
                                           value="<?= number_format($produto['preco'], 2, ',', '') ?>">
                                </div>
                                <div class="invalid-feedback">
                                    Por favor, informe um preço válido.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="estoque" class="form-label">Estoque</label>
                                <input type="number" class="form-control" id="estoque" name="estoque" required min="0"
                                       value="<?= (int)$produto['estoque'] ?>">
                                <div class="invalid-feedback">
                                    Por favor, informe uma quantidade válida.
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Categoria</dt>
                        <dd class="col-sm-7">
                            <?php if ($produto['categoria_icone']): ?>
                                <i class="bi bi-<?= htmlspecialchars($produto['categoria_icone']) ?>"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($produto['nome_categoria']) ?>
                        </dd>

                        <dt class="col-sm-5">Preço Custo</dt>
                        <dd class="col-sm-7">
                            <?php if ($produto['preco_custo'] > 0): ?>
                                R$ <?= number_format($produto['preco_custo'], 2, ',', '.') ?>
                            <?php else: ?>
                                <span class="text-muted">Não informado</span>
                            <?php endif; ?>
                        </dd>

                        <dt class="col-sm-5">Preço Venda</dt>
                        <dd class="col-sm-7">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></dd>

                        <?php 
                        // Calcular lucro
                        $lucro_valor = 0;
                        $lucro_percentual = 0;
                        if ($produto['preco_custo'] > 0) {
                            $lucro_valor = $produto['preco'] - $produto['preco_custo'];
                            $lucro_percentual = ($lucro_valor / $produto['preco_custo']) * 100;
                        }
                        ?>
                        <dt class="col-sm-5">Lucro Unitário</dt>
                        <dd class="col-sm-7">
                            <?php if ($produto['preco_custo'] > 0): ?>
                                <span class="<?= $lucro_valor >= 0 ? 'text-success' : 'text-danger' ?>">
                                    R$ <?= number_format($lucro_valor, 2, ',', '.') ?>
                                    (<?= number_format($lucro_percentual, 1) ?>%)
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </dd>

                        <dt class="col-sm-5">Estoque</dt>
                        <dd class="col-sm-7"><?= $produto['estoque'] ?> unidade(s)</dd>

                        <?php 
                        // Calcular valor do estoque (preço de venda x estoque)
                        $valor_estoque = $produto['preco'] * $produto['estoque'];
                        ?>
                        <dt class="col-sm-5">Valor Estoque</dt>
                        <dd class="col-sm-7">
                            <strong class="text-primary">
                                R$ <?= number_format($valor_estoque, 2, ',', '.') ?>
                            </strong>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Formatar campo de preço de custo
document.getElementById('preco_custo').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    value = (parseInt(value || '0') / 100).toFixed(2);
    e.target.value = value.replace('.', ',');
});

// Formatar campo de preço
document.getElementById('preco').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    value = (parseInt(value || '0') / 100).toFixed(2);
    e.target.value = value.replace('.', ',');
});

// Validação do formulário
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php include 'includes/footer.php'; ?>
