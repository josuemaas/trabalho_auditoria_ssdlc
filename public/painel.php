<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto SSDLC</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <header class="cabecalho">
        <div class="cabecalho-conteudo">
            <h1>Atividade Avaliativa - SSDLC</h1>
            <p>Auditoria e Segurança de Sistemas</p>
        </div>
    </header>

    <main class="container">

        <div class="card">
            <div class="topo">
                <div>
                    <h2>Projeto Acadêmico</h2>

                    <p>
                        Bem-vindo,
                        <?= htmlspecialchars(
                            $_SESSION['usuario'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>.
                    </p>
                </div>

                <a href="logout.php" class="botao botao-secundario">
                    Sair
                </a>
            </div>

            <p class="descricao-projeto">
                Sistema desenvolvido para demonstrar práticas de
                desenvolvimento seguro utilizando conceitos de SSDLC.
                A aplicação possui autenticação, controle de sessão,
                proteção contra SQL Injection, XSS e CSRF.
            </p>
        </div>

        <div class="card">
            <h2>Funcionalidades</h2>

            <p class="descricao-projeto">
                O sistema permite o gerenciamento de tarefas por meio
                das operações de cadastro, consulta, edição e exclusão.
            </p>

            <a href="tarefas.php" class="botao">
                Gerenciar tarefas
            </a>
        </div>

        <div class="card">
            <h2>Tecnologias utilizadas</h2>

            <div class="informacoes">

                <div class="informacao">
                    <strong>Aplicação</strong>
                    PHP
                </div>

                <div class="informacao">
                    <strong>Banco de dados</strong>
                    SQLite
                </div>

                <div class="informacao">
                    <strong>Versionamento</strong>
                    GitHub
                </div>

                <div class="informacao">
                    <strong>Pipeline</strong>
                    GitHub Actions
                </div>

                <div class="informacao">
                    <strong>Segurança</strong>
                    SonarQube
                </div>

                <div class="informacao">
                    <strong>Hospedagem</strong>
                    AWS EC2
                </div>

            </div>
        </div>

    </main>

    <footer class="rodape">
        Trabalho acadêmico - Auditoria e Segurança de Sistemas
    </footer>

</body>
</html>