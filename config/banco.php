<?php

declare(strict_types=1);

$caminhoBanco = __DIR__ . '/../database/sistema.sqlite';

$pdo = new PDO('sqlite:' . $caminhoBanco);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);