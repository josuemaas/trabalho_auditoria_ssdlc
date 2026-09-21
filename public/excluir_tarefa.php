<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/../config/banco.php';
require __DIR__ . '/../src/services/CsrfService.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$token = $_POST['csrf_token'] ?? null;

if (!$id || !validarTokenCsrf($token)) {
    http_response_code(403);
    exit('Requisição inválida.');
}

$stmt = $pdo->prepare(
    'DELETE FROM tarefas
     WHERE id = :id'
);

$stmt->execute([
    ':id' => $id
]);

header('Location: tarefas.php');
exit;