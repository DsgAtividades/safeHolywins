<?php
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';
//verificarLogin();
// Buscar algumas estatísticas básicas
$stats = [
    'total_pessoas' => 0,
    'total_produtos' => 0,
    'total_vendas' => 0,
    'saldo_total' => 0
];

try {
    // Total de pessoas
    if (temPermissao('gerenciar_pessoas')) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM pessoas");
        $stats['total_pessoas'] = $stmt->fetch()['total'];
    }

    // Total de produtos
    if (temPermissao('gerenciar_produtos')) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
        $stats['total_produtos'] = $stmt->fetch()['total'];
    }

    // Total de vendas
    if (temPermissao('gerenciar_vendas')) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM vendas");
        $stats['total_vendas'] = $stmt->fetch()['total'];
    }

    // Saldo total em cartões
    if (temPermissao('gerenciar_transacoes')) {
        $stmt = $pdo->query("SELECT SUM(saldo) as total FROM saldos_cartao");
        $stats['saldo_total'] = $stmt->fetch()['total'] ?? 0;
    }
} catch(PDOException $e) {
    // Ignora erros caso as tabelas ainda não existam
}

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                <i class="bi bi-house-door text-primary"></i> Dashboard Principal
            </h1>
            <p class="text-muted mb-0">Bem-vindo ao Sistema de Gestão da Festa Junina</p>
        </div>
        <div class="text-end">
            <small class="text-muted">
                <i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?> 
                <i class="bi bi-clock ms-2"></i> <?= date('H:i') ?>
            </small>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4">
        <?php if (temPermissao('gerenciar_pessoas')): ?>
        <div class="col-md-6 col-xl-3 col-sm-12">
            <div class="card hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total de Pessoas</h6>
                        </div>
                        <div class="icon-shape bg-primary text-white rounded-circle p-3">
                            <i class="bi bi-people" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 font-weight-bold"><?= number_format($stats['total_pessoas'], 0, ',', '.') ?></h2>
                    <div class="mt-3">
                        <a href="pessoas.php" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-arrow-right-circle"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (temPermissao('gerenciar_produtos')): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total de Produtos</h6>
                        </div>
                        <div class="icon-shape bg-success text-white rounded-circle p-3">
                            <i class="bi bi-box-seam" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 font-weight-bold"><?= number_format($stats['total_produtos'], 0, ',', '.') ?></h2>
                    <div class="mt-3">
                        <a href="produtos.php" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-arrow-right-circle"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (temPermissao('gerenciar_dashboard')): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total de Vendas</h6>
                        </div>
                        <div class="icon-shape bg-warning text-white rounded-circle p-3">
                            <i class="bi bi-cart-check" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 font-weight-bold"><?= number_format($stats['total_vendas'], 0, ',', '.') ?></h2>
                    <div class="mt-3">
                        <a href="vendas.php" class="btn btn-warning btn-sm w-100 text-white">
                            <i class="bi bi-arrow-right-circle"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (temPermissao('gerenciar_saldo_total')): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">Saldo Total</h6>
                        </div>
                        <div class="icon-shape bg-info text-white rounded-circle p-3">
                            <i class="bi bi-wallet2" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 font-weight-bold">R$ <?= number_format($stats['saldo_total'], 2, ',', '.') ?></h2>
                    <div class="mt-3">
                        <a href="saldos_lista.php" class="btn btn-info btn-sm w-100 text-white">
                            <i class="bi bi-arrow-right-circle"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Ações Rápidas -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning-charge"></i> Ações Rápidas
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <?php if (temPermissao('gerenciar_vendas') || temPermissao('gerenciar_vendas_mobile')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="vendas_mobile.php" class="btn btn-warning w-100 py-3 text-white shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-cart-plus d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Nova Venda</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_transacoes')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="saldos_mobile.php" class="btn btn-info w-100 py-3 text-white shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-cash-coin d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Adicionar Crédito</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_cartoes')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="alocar_cartao_mobile.php" class="btn btn-primary w-100 py-3 shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-credit-card-2-front d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Entrada Festa</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_produtos')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="produtos_novo.php" class="btn btn-success w-100 py-3 shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-plus-square d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Novo Produto</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_pessoas')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="pessoas_novo.php" class="btn btn-primary w-100 py-3 shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-person-plus d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Nova Pessoa</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_geracao_cartoes')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="gerar_cartoes.php" class="btn btn-dark w-100 py-3 shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-qr-code d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Gerar Cartões</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_dashboard')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="dashboard_vendas.php" class="btn btn-danger w-100 py-3 shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-graph-up-arrow d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Dashboard Vendas</strong>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('visualizar_relatorios')): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="relatorios.php" class="btn btn-secondary w-100 py-3 shadow-sm hover-lift" style="border-radius: 0.5rem;">
                                <i class="bi bi-file-earmark-bar-graph d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Relatórios</strong>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
