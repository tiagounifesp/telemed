<?php
session_start(); // Inicia a sessão

include "php/conn.php";



// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Aplica MD5 à senha

    // Consulta o banco de dados para verificar as credenciais
    $sql = "SELECT * FROM tbusuarios WHERE login = '$username' AND senha = '$password'";
    $result = $conn->query($sql);

    echo $result->num_rows;

    if ($result->num_rows > 0) {
        // Login bem-sucedido
        $row = $result->fetch_assoc();

        // Armazena informações do usuário na sessão
        $_SESSION['usuario'] = $row['nome'];
        $_SESSION['privilegio'] = $row['privilegio'];

        // Redireciona para a página inicial
        header("Location: medicos.php");
        exit(); // Encerra o script após o redirecionamento
    } else {
        // Login falhou
        $msg = "Usuário ou senha incorretos.";
    }
}

$conn->close(); // Fecha a conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Estilo global */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        /* Container do login */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        /* Caixa do login */
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        /* Título do login */
        .login-box h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        /* Estilo dos inputs */
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 16px;
        }

        /* Estilo do botão */
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Mensagem de erro */
        .alert {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="text-center">Login</h2>
            <?php
            // Exibe mensagem de erro, se houver
            if (isset($msg)) {
                echo '<div class="alert alert-danger">' . $msg . '</div>';
            }
            ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>