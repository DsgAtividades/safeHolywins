<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Aceitar tanto GET quanto POST
$qr_code = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $qr_code = $data['codigo'] ?? null;
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $qr_code = $_GET['qr_code'] ?? null;
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Verificar se o código foi informado
if (!$qr_code) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'QR Code não informado']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $resultado = [
        'existe' => false,
        'usado' => false,
        'tipo' => '',
        'pessoa' => '',
        'cartao' => null
    ];
    
    // Verificar se está em cartões
    $stmt = $db->prepare("SELECT usado, id_pessoa FROM cartoes WHERE codigo = ?");
    $stmt->execute([$qr_code]);
    $cartao = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cartao) {
        $resultado['existe'] = true;
        $resultado['tipo'] = 'cartao';
        $resultado['usado'] = $cartao['usado'] == 1 || $cartao['usado'] == true;
        $resultado['cartao'] = [
            'usado' => $cartao['usado'] == 1 || $cartao['usado'] == true,
            'id_pessoa' => $cartao['id_pessoa']
        ];
        
        // Se está sendo usado, buscar dados da pessoa
        if ($resultado['usado'] && $cartao['id_pessoa']) {
            $stmt = $db->prepare("SELECT nome FROM pessoas WHERE id_pessoa = ?");
            $stmt->execute([$cartao['id_pessoa']]);
            $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($pessoa) {
                $resultado['pessoa'] = $pessoa['nome'];
                
                // Buscar saldo da pessoa
                $stmt = $db->prepare("SELECT saldo FROM saldos_cartao WHERE id_pessoa = ?");
                $stmt->execute([$cartao['id_pessoa']]);
                $saldo = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($saldo) {
                    $resultado['cartao']['saldo'] = $saldo['saldo'];
                }
            }
        }
    }
    
    // Verificar se está em pessoas (onde qrcode referencia cartoes)
    $stmt = $db->prepare("SELECT id_pessoa, nome FROM pessoas WHERE qrcode = ?");
    $stmt->execute([$qr_code]);
    $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pessoa) {
        $resultado['existe'] = true;
        $resultado['tipo'] = 'pessoa';
        $resultado['usado'] = true;
        $resultado['pessoa'] = $pessoa['nome'];
        
        // Buscar saldo se existir
        $stmt = $db->prepare("SELECT saldo FROM saldos_cartao WHERE id_pessoa = ?");
        $stmt->execute([$pessoa['id_pessoa']]);
        $saldo = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($saldo) {
            $resultado['cartao'] = $resultado['cartao'] ?? [];
            $resultado['cartao']['saldo'] = $saldo['saldo'];
        }
    }
    
    echo json_encode($resultado);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao verificar QR Code: ' . $e->getMessage()]);
}
