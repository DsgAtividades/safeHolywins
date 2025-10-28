<?php
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';

verificarPermissao('gerenciar_saldos_historicos');

// Filtros de data
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : date('Y-m-d');
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : date('Y-m-d');

// Buscar motivos distintos
$stmt = $pdo->query("SELECT DISTINCT motivo FROM historico_saldo WHERE tipo_operacao = 'credito'");
$motivos = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Consulta agrupada por dia e motivo
$stmt = $pdo->prepare("
    SELECT DATE(create_at) as dia, nome_usuario,
    -- COUNT(CASE WHEN tipo = 'Debito' THEN id_transacao END) AS qtd_debito,
    SUM(CASE WHEN tipo = 'Debito' THEN ABS(valor) ELSE 0 END) AS total_debito,
    -- COUNT(CASE WHEN tipo = 'Credito' THEN id_transacao END) AS qtd_credito,
    SUM(CASE WHEN tipo = 'Credito' THEN ABS(valor) ELSE 0 END) AS total_credito,
    -- COUNT(CASE WHEN tipo = 'PIX' THEN id_transacao END) AS qtd_pix,
    SUM(CASE WHEN tipo = 'PIX' THEN ABS(valor) ELSE 0 END) AS total_pix,
    -- COUNT(CASE WHEN tipo = 'Dinheiro' THEN id_transacao END) AS qtd_dinheiro,
    SUM(CASE WHEN tipo = 'Dinheiro' THEN ABS(valor) ELSE 0 END) AS total_dinheiro,
    -- COUNT(CASE WHEN tipo = 'Custo CartÃ£o' THEN id_transacao END) AS qtd_custo_cartao,
    SUM(CASE WHEN tipo = 'Custo Cartão' THEN ABS(valor) ELSE 0 END) AS total_custo_cartao,
    SUM(CASE WHEN tipo = 'Estorno' THEN ABS(valor) ELSE 0 END) AS total_custo_estorno,
    (
    SUM(CASE WHEN tipo = 'Debito' THEN ABS(valor) ELSE 0 END) +
    SUM(CASE WHEN tipo = 'Credito' THEN ABS(valor) ELSE 0 END) +
    SUM(CASE WHEN tipo = 'PIX' THEN ABS(valor) ELSE 0 END) +
    SUM(CASE WHEN tipo = 'Dinheiro' THEN ABS(valor) ELSE 0 END) +
    SUM(CASE WHEN tipo = 'Custo Cartão' THEN ABS(valor) ELSE 0 END) - 
    SUM(CASE WHEN tipo = 'Estorno' THEN ABS(valor) ELSE 0 END)
    ) AS total_geral
    FROM historico_transacoes_sistema
    WHERE DATE(create_at) between ? and ? AND grupo_usuario = 'Caixas'
    GROUP BY DATE(create_at), nome_usuario
    ORDER BY date(create_at) asc, nome_usuario;
");
$stmt->execute([$data_inicio, $data_fim]);
//$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar todos os dias do período
$periodo = [];
$dt = new DateTime($data_inicio);
$dt_fim = new DateTime($data_fim);
if($data_inicio != $data_fim){
    while ($dt <= $dt_fim) {
        $periodo[] = $dt->format('Y-m-d');
        $dt->modify('+1 day');
    }
}else{
    $periodo[] = $dt->format('Y-m-d');
}
$total_geral = 0;
$valor = 0;
include 'includes/header.php';
?>
<div class="container py-4">
    <!-- Título da Página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-calculator"></i> Fechamento de Caixa
                    </h6>
                    <a href="saldos.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-5">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>">
                </div>
                <div class="col-md-5">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Dia</th>    
                    <th>Operador</th>                     
                        <!-- <th>Qtd Débito</th> -->
                        <th>Total Débito</th>
                        <!-- <th>Qtd Crédito</th> -->
                        <th>Total Crédito</th>
                        <!-- <th>Qtd PIX</th> -->
                        <th>Total PIX</th>
                        <!-- <th>Qtd Dinheiro</th> -->
                        <th>Total Dinheiro</th>
                        <!-- <th>Qtd Custo Cartão</th> -->
                        <th>Total Custo Cartão</th>
                        <th>Total Estorno</th>
                        <th>Total do Dia</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  ?>
                            <tr>
                            <?php foreach ($row as $dado):
                                $valor = $dado; ?>
                                <td><?=htmlspecialchars($dado)?></td>
                            <?php endforeach; ?>
                            </tr>
                        
                <?php $total_geral+= $valor; } ?>
                <tr>
                    <td colspan="8">Total Geral</td>
                    <td><?=$total_geral?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const inicio = new Date(document.getElementById('data_inicio').value);
    const fim = new Date(document.getElementById('data_fim').value);
    if (fim < inicio) {
        e.preventDefault();
        alert('A data final não pode ser anterior à data inicial.');
    }
});
</script>
<?php include 'includes/footer.php'; ?> 