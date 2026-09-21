<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/../config/banco.php';
require __DIR__ . '/../src/services/CsrfService.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: tarefas.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT id, titulo, descricao
     FROM tarefas
     WHERE id = :id'
);

$stmt->execute([
    ':id' => $id
]);

$tarefa = $stmt->fetch();

if (!$tarefa) {
    header('Location: tarefas.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? null;

    if (!validarTokenCsrf($token)) {
        http_response_code(403);
        exit('Requisição inválida.');
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($titulo === '' || $descricao === '') {
        $mensagem = 'Preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare(
            'UPDATE tarefas
             SET titulo = :titulo,
                 descricao = :descricao
             WHERE id = :id'
        );

        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':id' => $id
        ]);

        header('Location: tarefas.php');
        exit;
    }
}

$csrfToken = gerarTokenCsrf();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarefa</title>
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
                    <h1>Editar tarefa</h1>
                    <p class="subtitulo">
                        Atualização de registro com validação e proteção
                        contra requisições indevidas.
                    </p>
                </div>

                <a href="tarefas.php" class="botao botao-secundario">
                    Voltar
                </a>
            </div>

            <?php if ($mensagem !== ''): ?>
                <p class="mensagem">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="POST" class="formulario-secao">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
                >

                <div class="formulario-grid">
                    <div class="campo-grupo">
                        <label for="titulo">Título</label>
                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            maxlength="100"
                            value="<?= htmlspecialchars(
                                $tarefa['titulo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >
                    </div>

                    <div class="campo-grupo">
                        <label for="descricao">Descrição</label>
                        <textarea
                            id="descricao"
                            name="descricao"
                            maxlength="500"
                            required
                        ><?= htmlspecialchars(
                            $tarefa['descricao'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?></textarea>
                    </div>
                </div>

                <div class="acoes-formulario">
                    <button type="submit">Salvar alterações</button>
                    <span class="badge">Edição protegida por token CSRF</span>
                </div>
            </form>
        </div>
    </main>

    <footer class="rodape">
        Trabalho acadêmico - Desenvolvimento Seguro de Software (SSDLC)
    </footer>

</body>
</html>