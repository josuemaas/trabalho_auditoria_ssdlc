<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/../config/banco.php';
require __DIR__ . '/../src/services/CsrfService.php';

$mensagem = '';

if (isset($_GET['sucesso'])) {
    $mensagem = 'Tarefa cadastrada com sucesso.';
}

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
        $sql = '
            INSERT INTO tarefas (
                titulo,
                descricao,
                criado_em
            )
            VALUES (
                :titulo,
                :descricao,
                :criado_em
            )
        ';

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':criado_em' => date('Y-m-d H:i:s')
        ]);

        header('Location: tarefas.php?sucesso=1');
        exit;
    }
}

$stmt = $pdo->prepare(
    'SELECT id, titulo, descricao, criado_em
     FROM tarefas
     ORDER BY id DESC'
);

$stmt->execute();

$tarefas = $stmt->fetchAll();

$csrfToken = gerarTokenCsrf();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
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
                    <h1>Gerenciamento de Tarefas</h1>
                    <p class="subtitulo">
                        Módulo acadêmico para demonstração de cadastro,
                        consulta, edição e exclusão de registros com
                        práticas de desenvolvimento seguro.
                    </p>
                </div>

                <a href="painel.php" class="botao botao-secundario">
                    Voltar para o painel
                </a>
            </div>

            <div class="resumo-grid">
                <div class="resumo-item">
                    <strong>Total de tarefas</strong>
                    <span><?= count($tarefas) ?> registro(s) cadastrado(s)</span>
                </div>

                <div class="resumo-item">
                    <strong>Usuário autenticado</strong>
                    <span><?= htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <div class="resumo-item">
                    <strong>Segurança aplicada</strong>
                    <span>PDO, CSRF, sessões e proteção contra XSS</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Cadastro de nova tarefa</h2>

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
                        ></textarea>
                    </div>
                </div>

                <div class="acoes-formulario">
                    <button type="submit">Cadastrar tarefa</button>
                    <span class="badge">Entrada validada com proteção CSRF</span>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Tarefas cadastradas</h2>

            <?php if (count($tarefas) === 0): ?>
                <p>Nenhuma tarefa cadastrada.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($tarefas as $tarefa): ?>
                                <tr>
                                    <td><?= (int) $tarefa['id'] ?></td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $tarefa['titulo'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $tarefa['descricao'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $tarefa['criado_em'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <div class="acoes">
                                            <a
                                                href="editar_tarefa.php?id=<?= (int) $tarefa['id'] ?>"
                                                class="botao"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                method="POST"
                                                action="excluir_tarefa.php"
                                                style="margin: 0;"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $tarefa['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= htmlspecialchars(
                                                        $csrfToken,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="botao-excluir"
                                                >
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="rodape">
        Trabalho acadêmico - Desenvolvimento Seguro de Software (SSDLC)
    </footer>

</body>
</html>