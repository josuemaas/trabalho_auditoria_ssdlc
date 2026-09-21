<?php

declare(strict_types=1);

session_start();

$usuarios = require __DIR__ . '/../config/usuarios.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioInformado = trim($_POST['usuario'] ?? '');
    $senhaInformada = $_POST['senha'] ?? '';

    foreach ($usuarios as $usuario) {
        if (
            $usuario['usuario'] === $usuarioInformado &&
            password_verify($senhaInformada, $usuario['senha'])
        ) {
            session_regenerate_id(true);

            $_SESSION['usuario'] = $usuario['usuario'];

            header('Location: painel.php');
            exit;
        }
    }

    $erro = 'Usuário ou senha inválidos.';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="login-container">
        <div class="card">
            <h1>Login</h1>

            <?php if ($erro !== ''): ?>
                <p class="mensagem">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="POST">
                <label for="usuario">Usuário</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    required
                >

                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    required
                >

                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>