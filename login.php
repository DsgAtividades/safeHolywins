<?php
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';
session_start();

//Se já estiver logado, redireciona para index.php
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        try {
            $stmt = $pdo->prepare("
                SELECT u.*, g.nome as grupo_nome 
                FROM usuarios u
                LEFT JOIN grupos g ON u.grupo_id = g.id
                WHERE u.email = ? AND u.ativo = 1
            ");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_grupo'] = $usuario['grupo_nome'];
                $_SESSION['projeto'] = 'paroquia';

                // Buscar permissões do usuário
                $stmt = $pdo->prepare("
                    SELECT p.nome
                    FROM permissoes p
                    JOIN grupos_permissoes gp ON p.id = gp.permissao_id
                    WHERE gp.grupo_id = ?
                ");
                $stmt->execute([$usuario['grupo_id']]);
                $_SESSION['usuario_permissoes'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                // Verificar se é primeiro login
                if (isset($usuario['primeiro_login']) && $usuario['primeiro_login'] == 1) {
                    $_SESSION['primeiro_login'] = true;
                    header("Location: primeiro_login.php");
                    exit;
                }
                
                header("Location: index.php");
                exit;
            } else {
                exibirAlerta("Email ou senha incorretos", "danger");
            }
        } catch (PDOException $e) {
            exibirAlerta("Erro ao realizar login: " . $e->getMessage(), "danger");
        }
    } else {
        exibirAlerta("Por favor, preencha todos os campos", "danger");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Festa Junina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Padrão de fundo */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 1rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem 2.5rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .login-header .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #718096;
            font-size: 0.95rem;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            letter-spacing: 0.3px;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            padding-left: 3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f7fafc;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.4rem;
            color: #a0aec0;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: #667eea;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .btn-login i {
            margin-right: 0.5rem;
        }

        /* Alertas personalizados */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border-left: 4px solid #dc2626;
            color: #991b1b;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: white;
            font-size: 0.875rem;
        }

        .login-footer a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Responsividade */
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .login-header .icon-wrapper {
                width: 70px;
                height: 70px;
            }

            .login-header .icon-wrapper i {
                font-size: 2rem;
            }
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon-wrapper">
                    <i class="bi bi-emoji-smile"></i>
                </div>
                <h1>Sistema Festa Junina</h1>
                <p>Faça login para continuar</p>
            </div>

            <?php mostrarAlerta(); ?>

            <form method="post" action="login.php" id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope-fill"></i> E-mail
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="seu@email.com"
                           required
                           autofocus
                           value="<?= isset($_POST['email']) ? escapar($_POST['email']) : '' ?>">
                    <i class="bi bi-envelope-fill input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="senha" class="form-label">
                        <i class="bi bi-lock-fill"></i> Senha
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="senha" 
                           name="senha" 
                           placeholder="••••••••"
                           required>
                    <i class="bi bi-lock-fill input-icon"></i>
                </div>

                <button type="submit" class="btn btn-login" id="btnLogin">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Entrar no Sistema
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p>&copy; <?= date('Y') ?> Sistema Festa Junina - Todos os direitos reservados</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btnLogin = document.getElementById('btnLogin');
            btnLogin.classList.add('loading');
            btnLogin.innerHTML = '<span style="opacity: 0;">Entrando...</span>';
        });
    </script>
</body>
</html>
