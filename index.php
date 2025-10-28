<?php
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';

// Buscar estatísticas completas
$stats = [
    'total_pessoas' => 0,
    'total_produtos' => 0,
    'total_vendas' => 0,
    'total_vendas_hoje' => 0,
    'total_vendas_mes' => 0,
    'saldo_total' => 0,
    'valor_vendas_hoje' => 0,
    'valor_vendas_mes' => 0,
    'produtos_estoque_baixo' => 0,
    'cartoes_ativos' => 0,
    'cartoes_inativos' => 0
];

$ultimas_vendas = [];
$produtos_mais_vendidos = [];
$produtos_estoque_baixo = [];
$atividades_recentes = [];
$vendas_por_dia = [];

try {
    // Total de pessoas
    if (temPermissao('gerenciar_pessoas')) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM pessoas");
        $stats['total_pessoas'] = $stmt->fetch()['total'];
    }

    // Total de produtos e produtos com estoque baixo
    if (temPermissao('gerenciar_produtos')) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE status = 'ativo'");
        $stats['total_produtos'] = $stmt->fetch()['total'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE estoque_atual <= estoque_minimo AND status = 'ativo'");
        $stats['produtos_estoque_baixo'] = $stmt->fetch()['total'];
        
        // Produtos com estoque baixo
        $stmt = $pdo->query("SELECT nome, estoque_atual, estoque_minimo FROM produtos 
                            WHERE estoque_atual <= estoque_minimo AND status = 'ativo' 
                            ORDER BY estoque_atual ASC LIMIT 5");
        $produtos_estoque_baixo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Estatísticas de vendas
    if (temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')) {
        // Total de vendas
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM vendas");
        $stats['total_vendas'] = $stmt->fetch()['total'];
        
        // Vendas de hoje
        $stmt = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(valor_total), 0) as valor 
                            FROM vendas WHERE DATE(data_venda) = CURDATE()");
        $hoje = $stmt->fetch();
        $stats['total_vendas_hoje'] = $hoje['total'];
        $stats['valor_vendas_hoje'] = $hoje['valor'];
        
        // Vendas do mês
        $stmt = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(valor_total), 0) as valor 
                            FROM vendas WHERE MONTH(data_venda) = MONTH(CURDATE()) 
                            AND YEAR(data_venda) = YEAR(CURDATE())");
        $mes = $stmt->fetch();
        $stats['total_vendas_mes'] = $mes['total'];
        $stats['valor_vendas_mes'] = $mes['valor'];
        
        // Últimas vendas
        $stmt = $pdo->query("SELECT v.id, v.data_venda, v.valor_total, p.nome as pessoa_nome, 
                            u.nome as usuario_nome
                            FROM vendas v
                            LEFT JOIN pessoas p ON v.id_pessoa = p.id
                            LEFT JOIN usuarios u ON v.id_usuario = u.id
                            ORDER BY v.data_venda DESC LIMIT 10");
        $ultimas_vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Vendas dos últimos 7 dias para gráfico
        $stmt = $pdo->query("SELECT DATE(data_venda) as data, COUNT(*) as total, 
                            COALESCE(SUM(valor_total), 0) as valor
                            FROM vendas 
                            WHERE data_venda >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                            GROUP BY DATE(data_venda)
                            ORDER BY data ASC");
        $vendas_por_dia = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Produtos mais vendidos
        $stmt = $pdo->query("SELECT p.nome, SUM(vi.quantidade) as total_vendido, 
                            COALESCE(SUM(vi.quantidade * vi.preco_unitario), 0) as valor_total
                            FROM vendas_itens vi
                            INNER JOIN produtos p ON vi.id_produto = p.id
                            INNER JOIN vendas v ON vi.id_venda = v.id
                            WHERE v.data_venda >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                            GROUP BY vi.id_produto
                            ORDER BY total_vendido DESC LIMIT 5");
        $produtos_mais_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Saldo total em cartões e status
    if (temPermissao('gerenciar_transacoes')) {
        $stmt = $pdo->query("SELECT COALESCE(SUM(saldo), 0) as total FROM saldos_cartao WHERE status = 'ativo'");
        $stats['saldo_total'] = $stmt->fetch()['total'] ?? 0;
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM saldos_cartao WHERE status = 'ativo'");
        $stats['cartoes_ativos'] = $stmt->fetch()['total'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM saldos_cartao WHERE status = 'inativo'");
        $stats['cartoes_inativos'] = $stmt->fetch()['total'];
    }
    
} catch(PDOException $e) {
    // Ignora erros caso as tabelas ainda não existam
}

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Cabeçalho da Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-speedometer2 text-primary"></i> Dashboard - Festa Junina</h2>
            <p class="text-muted mb-0">Visão geral do sistema em tempo real</p>
        </div>
        <div class="text-end">
            <small class="text-muted">
                <i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?> - 
                <i class="bi bi-clock"></i> <?= date('H:i') ?>
            </small>
        </div>
    </div>
    
    <!-- Cards de Estatísticas Principais -->
    <div class="row g-4 mb-4">
        <?php if (temPermissao('gerenciar_pessoas')): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Total de Pessoas</h6>
                            <h2 class="mb-0 fw-bold text-primary"><?= number_format($stats['total_pessoas'], 0, ',', '.') ?></h2>
                        </div>
                        <div class="text-primary opacity-75">
                            <i class="bi bi-people display-4"></i>
                        </div>
                    </div>
                    <a href="pessoas.php" class="btn btn-primary btn-sm mt-3 w-100">
                        <i class="bi bi-eye"></i> Ver Todas
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (temPermissao('gerenciar_produtos')): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Total de Produtos</h6>
                            <h2 class="mb-0 fw-bold text-success"><?= number_format($stats['total_produtos'], 0, ',', '.') ?></h2>
                            <?php if ($stats['produtos_estoque_baixo'] > 0): ?>
                                <small class="text-danger">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    <?= $stats['produtos_estoque_baixo'] ?> com estoque baixo
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="text-success opacity-75">
                            <i class="bi bi-box-seam display-4"></i>
                        </div>
                    </div>
                    <a href="produtos.php" class="btn btn-success btn-sm mt-3 w-100">
                        <i class="bi bi-eye"></i> Ver Todos
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Vendas Hoje</h6>
                            <h2 class="mb-0 fw-bold text-warning"><?= number_format($stats['total_vendas_hoje'], 0, ',', '.') ?></h2>
                            <small class="text-muted">
                                R$ <?= number_format($stats['valor_vendas_hoje'], 2, ',', '.') ?>
                            </small>
                        </div>
                        <div class="text-warning opacity-75">
                            <i class="bi bi-cart-check display-4"></i>
                        </div>
                    </div>
                    <a href="vendas.php" class="btn btn-warning btn-sm mt-3 w-100">
                        <i class="bi bi-eye"></i> Ver Vendas
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (temPermissao('gerenciar_transacoes')): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Saldo Total</h6>
                            <h2 class="mb-0 fw-bold text-info">R$ <?= number_format($stats['saldo_total'], 2, ',', '.') ?></h2>
                            <small class="text-muted">
                                <?= $stats['cartoes_ativos'] ?> cartões ativos
                            </small>
                        </div>
                        <div class="text-info opacity-75">
                            <i class="bi bi-wallet2 display-4"></i>
                        </div>
                    </div>
                    <a href="saldos.php" class="btn btn-info btn-sm mt-3 w-100 text-white">
                        <i class="bi bi-eye"></i> Ver Saldos
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Segunda linha de cards - Resumo Mensal -->
    <?php if (temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')): ?>
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-month text-primary display-5 mb-2"></i>
                    <h6 class="text-muted mb-2">Vendas do Mês</h6>
                    <h3 class="mb-0 text-primary"><?= number_format($stats['total_vendas_mes'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-currency-dollar text-success display-5 mb-2"></i>
                    <h6 class="text-muted mb-2">Faturamento do Mês</h6>
                    <h3 class="mb-0 text-success">R$ <?= number_format($stats['valor_vendas_mes'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up text-info display-5 mb-2"></i>
                    <h6 class="text-muted mb-2">Ticket Médio</h6>
                    <h3 class="mb-0 text-info">
                        R$ <?= $stats['total_vendas_mes'] > 0 ? number_format($stats['valor_vendas_mes'] / $stats['total_vendas_mes'], 2, ',', '.') : '0,00' ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Ações Rápidas -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php if (temPermissao('gerenciar_vendas') || temPermissao('gerenciar_vendas_mobile')): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="vendas_mobile.php" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 text-white">
                                <i class="bi bi-cart-plus display-6 mb-2"></i>
                                <span class="fw-bold">Nova Venda</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_transacoes')): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="saldos_mobile.php" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 text-white">
                                <i class="bi bi-cash display-6 mb-2"></i>
                                <span class="fw-bold">Adicionar Crédito</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_produtos')): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="produtos_novo.php" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-plus-square display-6 mb-2"></i>
                                <span class="fw-bold">Novo Produto</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_pessoas')): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="pessoas_novo.php" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-person-plus display-6 mb-2"></i>
                                <span class="fw-bold">Nova Pessoa</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_cartoes')): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="alocar_cartao_mobile.php" class="btn btn-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-credit-card display-6 mb-2"></i>
                                <span class="fw-bold">Alocar Cartão</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if (temPermissao('gerenciar_geracao_cartoes')): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="gerar_cartoes.php" class="btn btn-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-qr-code display-6 mb-2"></i>
                                <span class="fw-bold">Gerar Cartões</span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Gráfico de Vendas -->
        <?php if ((temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')) && !empty($vendas_por_dia)): ?>
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up text-primary"></i> Vendas dos Últimos 7 Dias
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="vendasChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Produtos Mais Vendidos -->
        <?php if ((temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')) && !empty($produtos_mais_vendidos)): ?>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy text-warning"></i> Top 5 Produtos
                    </h5>
                    <small class="text-muted">Últimos 30 dias</small>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($produtos_mais_vendidos as $index => $produto): ?>
                        <div class="list-group-item px-0 border-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-<?= $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') ?> me-2">
                                        <?= $index + 1 ?>º
                                    </span>
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($produto['nome']) ?></div>
                                        <small class="text-muted"><?= $produto['total_vendido'] ?> unidades</small>
                                    </div>
                                </div>
                                <span class="badge bg-success">
                                    R$ <?= number_format($produto['valor_total'], 2, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Últimas Vendas -->
        <?php if ((temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')) && !empty($ultimas_vendas)): ?>
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-info"></i> Últimas Vendas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" style="width: 80px;">ID</th>
                                    <th>Cliente</th>
                                    <th>Operador</th>
                                    <th class="text-center">Data/Hora</th>
                                    <th class="text-end">Valor</th>
                                    <th class="text-center" style="width: 100px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimas_vendas as $venda): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-primary">#<?= $venda['id'] ?></span>
                                    </td>
                                    <td>
                                        <i class="bi bi-person"></i> 
                                        <?= htmlspecialchars($venda['pessoa_nome'] ?? 'Não identificado') ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge"></i> 
                                        <?= htmlspecialchars($venda['usuario_nome'] ?? 'Sistema') ?>
                                    </td>
                                    <td class="text-center">
                                        <small><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></small>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="vendas_detalhes.php?id=<?= $venda['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver Detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Alertas de Estoque Baixo -->
        <?php if (temPermissao('gerenciar_produtos') && !empty($produtos_estoque_baixo)): ?>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-danger h-100">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Estoque Baixo
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($produtos_estoque_baixo as $produto): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-danger">
                                        <i class="bi bi-box"></i> <?= htmlspecialchars($produto['nome']) ?>
                                    </div>
                                    <small class="text-muted">Mínimo: <?= $produto['estoque_minimo'] ?></small>
                                </div>
                                <span class="badge bg-danger rounded-pill">
                                    <?= $produto['estoque_atual'] ?>
                                </span>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-danger" 
                                     style="width: <?= min(100, ($produto['estoque_atual'] / $produto['estoque_minimo']) * 100) ?>%">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer bg-light">
                        <a href="produtos_estoque.php" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-box-seam"></i> Gerenciar Estoque
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Status dos Cartões -->
        <?php if (temPermissao('gerenciar_transacoes')): ?>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="bi bi-credit-card-2-front text-success display-4 mb-3"></i>
                    <h6 class="text-muted mb-2">Cartões Ativos</h6>
                    <h2 class="text-success mb-0"><?= number_format($stats['cartoes_ativos'], 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="bi bi-credit-card-2-back text-secondary display-4 mb-3"></i>
                    <h6 class="text-muted mb-2">Cartões Inativos</h6>
                    <h2 class="text-secondary mb-0"><?= number_format($stats['cartoes_inativos'], 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Script do Gráfico -->
<?php if ((temPermissao('gerenciar_vendas') || temPermissao('gerenciar_dashboard')) && !empty($vendas_por_dia)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('vendasChart');
    if (ctx) {
        const labels = <?= json_encode(array_map(function($v) { return date('d/m', strtotime($v['data'])); }, $vendas_por_dia)) ?>;
        const valores = <?= json_encode(array_map(function($v) { return floatval($v['valor']); }, $vendas_por_dia)) ?>;
        const quantidades = <?= json_encode(array_map(function($v) { return intval($v['total']); }, $vendas_por_dia)) ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Valor (R$)',
                    data: valores,
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y',
                }, {
                    label: 'Quantidade',
                    data: quantidades,
                    borderColor: 'rgb(25, 135, 84)',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 0) {
                                        label += 'R$ ' + context.parsed.y.toFixed(2).replace('.', ',');
                                    } else {
                                        label += context.parsed.y + ' vendas';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toFixed(0);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });
    }
});
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
