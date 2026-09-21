<?php

declare(strict_types=1);

$caminhoBanco = __DIR__ . '/sistema.sqlite';

$pdo = new PDO('sqlite:' . $caminhoBanco);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = '
    CREATE TABLE IF NOT EXISTS tarefas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        descricao TEXT NOT NULL,
        criado_em TEXT NOT NULL
    )
';

$pdo->exec($sql);

echo 'Banco de dados criado com sucesso.' . PHP_EOL;