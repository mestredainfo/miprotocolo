<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

if (!defined('mi')) {
    exit;
}

$dbConfig['database'] = dirname(__FILE__) . '/dados/miprotocolo.sqlite';

function createDB()
{
    global $dbConfig;
    try {
        if (!file_exists(dirname(__FILE__) . '/dados/')) {
            mkdir(dirname(__FILE__) . '/dados/');
        }

        $db1 = new SQLite3($dbConfig['database']);

        $db1->exec("CREATE TABLE IF NOT EXISTS mi_options (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL
        );");

        $db1->exec("CREATE TABLE IF NOT EXISTS mi_clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL
        );");

        $db1->exec("CREATE TABLE IF NOT EXISTS mi_protocolos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        idcliente INTEGER NOT NULL,
        descricao TEXT NOT NULL
        )");

        $db1->close();
    } catch (SQLite3Exception $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo "Erro ao criar o banco de dados: " . $e->getMessage();
        exit;
    }
}
createDB();
