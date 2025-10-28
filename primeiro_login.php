<?php
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';
session_start();

// Verificar se o usuário está logado e é primeiro login
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['primeiro_login'])) {
    header("Location: login.php");
    exit;
}

$success_redirect = false;

// Processar formulário de mudança de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pular'])) {
        // Usuário escolheu pular a mudança de senha
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET primeiro_login = 0 WHERE id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            unset($_SESSION['primeiro_login']);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            exibirAlerta("Erro ao atualizar usuário: " . $e->getMessage(), "danger");
        }
    } elseif (isset($_POST['mudar_senha'])) {
        // Usuário escolheu mudar a senha
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';
        
        $erros = [];
        
        if (empty($nova_senha)) $erros[] = "Nova senha é obrigatória";
        if (strlen($nova_senha) < 6) $erros[] = "Nova senha deve ter no mínimo 6 caracteres";
        // Validação case sensitive - usando strcmp para comparação exata
        if (strcmp($nova_senha, $confirma_senha) !== 0) $erros[] = "As senhas não conferem (verificação case sensitive)";
        
        if (empty($erros)) {
            try {
                $stmt = $pdo->prepare("UPDATE usuarios SET senha = ?, primeiro_login = 0 WHERE id = ?");
                $stmt->execute([
                    password_hash($nova_senha, PASSWORD_DEFAULT),
                    $_SESSION['usuario_id']
                ]);
                
                unset($_SESSION['primeiro_login']);
                $success_redirect = true;
                
            } catch (PDOException $e) {
                exibirAlerta("Erro ao alterar senha: " . $e->getMessage(), "danger");
            }
        } else {
            foreach ($erros as $erro) {
                exibirAlerta($erro, "danger");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Login - Festa Junina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background-color: white;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header i {
            font-size: 3rem;
            color: #0d6efd;
        }
        .login-header h1 {
            font-size: 1.5rem;
            margin-top: 1rem;
            color: #333;
        }
        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        .welcome-message {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 0.5rem;
            padding: 1.2rem;
            margin-bottom: 2rem;
        }
        .welcome-message .user-name {
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 0.5rem;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .btn-group-custom {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .password-form {
            display: none;
        }
        .password-form.show {
            display: block;
        }
        .question-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .question-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .question-section p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Popup de Sucesso */
        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
        }
        
        .success-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
            z-index: 9999;
            display: none;
            min-width: 300px;
        }
        
        .success-popup .check-icon {
            width: 80px;
            height: 80px;
            background: #198754;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2.5rem;
            animation: checkPulse 0.6s ease-out;
        }
        
        .success-popup h4 {
            color: #198754;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .success-popup p {
            color: #6c757d;
            margin: 0;
        }
        
        @keyframes checkPulse {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Overlay para popup -->
    <div class="success-overlay" id="successOverlay"></div>
    
    <!-- Popup de sucesso -->
    <div class="success-popup" id="successPopup">
        <div class="check-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h4>Senha alterada com sucesso!</h4>
        <p>Redirecionando para o sistema...</p>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="bi bi-person-check-fill"></i>
                <h1>Primeiro Login</h1>
                <p>Configure sua conta para continuar</p>
            </div>

            <div class="welcome-message">
                <div class="user-name">Bem-vindo(a), <?= escapar($_SESSION['usuario_nome']) ?>!</div>
                <p class="mb-0">Este é seu primeiro acesso ao sistema. Por segurança, recomendamos que você altere sua senha.</p>
            </div>

            <?php mostrarAlerta(); ?>

            <!-- Pergunta inicial -->
            <div id="question-section">
                <div class="question-section">
                    <h3>Deseja alterar sua senha agora?</h3>
                    <p>Você pode fazer isso agora ou continuar com a senha atual.</p>
                </div>

                <div class="btn-group-custom">
                    <button type="button" class="btn btn-primary" onclick="showPasswordForm()">
                        <i class="bi bi-shield-lock"></i> Sim, alterar senha
                    </button>
                    
                    <form method="post" style="margin: 0;">
                        <button type="submit" name="pular" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-skip-forward"></i> Continuar sem alterar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Formulário de mudança de senha -->
            <div id="password-form" class="password-form">
                <form method="post" id="changePasswordForm">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="nova_senha" name="nova_senha" 
                               placeholder="Nova senha" required minlength="6">
                        <label for="nova_senha">Nova Senha</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" 
                               placeholder="Confirmar nova senha" required minlength="6">
                        <label for="confirma_senha">Confirmar Nova Senha</label>
                    </div>

                    <div class="btn-group-custom">
                        <button type="submit" name="mudar_senha" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Alterar Senha
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary" onclick="backToQuestion()">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showPasswordForm() {
            document.getElementById('question-section').style.display = 'none';
            document.getElementById('password-form').classList.add('show');
            document.getElementById('nova_senha').focus();
        }

        function backToQuestion() {
            document.getElementById('password-form').classList.remove('show');
            document.getElementById('question-section').style.display = 'block';
            
            // Limpar campos
            document.getElementById('nova_senha').value = '';
            document.getElementById('confirma_senha').value = '';
        }

        function showSuccessPopup() {
            const overlay = document.getElementById('successOverlay');
            const popup = document.getElementById('successPopup');
            
            overlay.style.display = 'block';
            popup.style.display = 'block';
            
            // Redirecionar após 1.5 segundos
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 1500);
        }

        // Validação em tempo real - CASE SENSITIVE
        document.addEventListener('DOMContentLoaded', function() {
            const novaSenhaInput = document.getElementById('nova_senha');
            const confirmaSenhaInput = document.getElementById('confirma_senha');
            
            function validarSenhas() {
                const novaSenha = novaSenhaInput.value;
                const confirmaSenha = confirmaSenhaInput.value;
                
                // Validação case sensitive - comparação exata
                if (confirmaSenha && novaSenha !== confirmaSenha) {
                    confirmaSenhaInput.setCustomValidity('As senhas não conferem (case sensitive)');
                    confirmaSenhaInput.classList.add('is-invalid');
                } else {
                    confirmaSenhaInput.setCustomValidity('');
                    confirmaSenhaInput.classList.remove('is-invalid');
                    if (confirmaSenha && novaSenha === confirmaSenha) {
                        confirmaSenhaInput.classList.add('is-valid');
                    }
                }
            }

            if (confirmaSenhaInput) {
                confirmaSenhaInput.addEventListener('input', validarSenhas);
                confirmaSenhaInput.addEventListener('blur', validarSenhas);
            }

            if (novaSenhaInput) {
                novaSenhaInput.addEventListener('input', function() {
                    if (confirmaSenhaInput.value) {
                        validarSenhas();
                    }
                });
            }
        });

        // Verificar se houve sucesso no processamento
        <?php if (isset($success_redirect) && $success_redirect === true): ?>
        // Mostrar popup de sucesso imediatamente
        showSuccessPopup();
        <?php endif; ?>
    </script>
</body>
</html>
