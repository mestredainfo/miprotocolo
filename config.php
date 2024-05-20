<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

if (!defined('mi')) {
    exit;
}

$dbConfig['database'] = '/home/' . get_current_user() . '/.miprotocolo/dados/miprotocolo.sqlite';

function createDB()
{
    global $dbConfig;

    try {
        if (file_exists(dirname(__FILE__) . '/update.txt')) {
            if (!file_exists('/home/' . get_current_user() . '/.miprotocolo/dados/')) {
                mkdir('/home/' . get_current_user() . '/.miprotocolo/dados/', 0777, true);
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

            unlink(dirname(__FILE__) . '/update.txt');
        }
    } catch (SQLite3Exception $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo "Erro ao criar o banco de dados: " . $e->getMessage();
        exit;
    }
}
createDB();
