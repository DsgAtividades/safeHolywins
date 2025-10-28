<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

// Verificar se foi passado um ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: categorias.php");
    exit;
}

$id = (int)$_GET['id'];

// Buscar dados da categoria
$stmt = $db->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header("Location: categorias.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $icone = trim($_POST['icone']);
    $ordem = (int)$_POST['ordem'];
    
    $erro = false;
    
    // Validações
    if (empty($nome)) {
        $erro = "O nome da categoria é obrigatório.";
    } else {
        // Verificar se já existe outra categoria com este nome
        $stmt = $db->prepare("SELECT COUNT(*) FROM categorias WHERE nome = ? AND id != ?");
        $stmt->execute([$nome, $id]);
        if ($stmt->fetchColumn() > 0) {
            $erro = "Já existe uma categoria com este nome.";
        }
    }
    
    if (!$erro) {
        try {
            $stmt = $db->prepare("UPDATE categorias SET nome = ?, icone = ?, ordem = ? WHERE id = ?");
            $stmt->execute([$nome, $icone, $ordem, $id]);
            
            header("Location: categorias.php");
            exit;
        } catch(PDOException $e) {
            $erro = "Erro ao atualizar categoria: " . $e->getMessage();
        }
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
                        <i class="bi bi-pencil-square"></i> Editar Categoria
                    </h6>
                    <a href="categorias.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               value="<?= htmlspecialchars($categoria['nome']) ?>">
                        <div class="invalid-feedback">
                            Por favor, informe o nome da categoria.
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="icone" class="form-label">Ícone</label>
                        <div class="input-group">
                            <span class="input-group-text">bi-</span>
                            <input type="text" class="form-control" id="icone" name="icone"
                                   value="<?= htmlspecialchars($categoria['icone']) ?>"
                                   placeholder="cart, bag-check, etc">
                        </div>
                        <div class="form-text">
                            Nome do ícone do <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="ordem" class="form-label">Ordem</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" 
                               value="<?= (int)$categoria['ordem'] ?>" min="0">
                        <div class="form-text">
                            Ordem de exibição (0 = primeiro)
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

    <div class="mt-4">
        <h3 class="h5 mb-3">Prévia do Ícone</h3>
        <div class="p-3 bg-light rounded">
            <i id="previewIcone" class="bi bi-<?= htmlspecialchars($categoria['icone']) ?> fs-3"></i>
        </div>
    </div>
</div>

<script>
// Preview do ícone
document.getElementById('icone').addEventListener('input', function(e) {
    const preview = document.getElementById('previewIcone');
    preview.className = 'bi bi-' + e.target.value + ' fs-3';
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
