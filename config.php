<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\arquivo;
use MISistema\app\pasta;
use MISistema\dados\ferramentas;
use MISistema\sistema\ambiente;

if (!defined('mi')) {
    exit;
}

$env = new ambiente();

$dbConfig['arquivo'] = $env->pastaUsuario() . '/.miprotocolo/dados/miprotocolo.sqlite';

function criarDB()
{
    global $dbConfig;

    try {
        $arquivo = new arquivo();
        $pasta = new pasta();
        if (!$arquivo->existe(dirname($dbConfig['arquivo']) . '/criardb.txt')) {
            if (!$arquivo->existe(dirname($dbConfig['arquivo']))) {
                $pasta->criar(dirname($dbConfig['arquivo']));
            }

            $db1 = new ferramentas($dbConfig);
            $db1->tabela('mi_options')
                ->autoIncrementar()
                ->chavePrimaria()
                ->inteiro()
                ->inserirColuna('id')
                ->inserirColuna('nome')
                ->criar();
            $db1->fechar();

            $db2 = new ferramentas($dbConfig);
            $db2->tabela('mi_clientes')
                ->autoIncrementar()
                ->chavePrimaria()
                ->inteiro()
                ->inserirColuna('id')
                ->inserirColuna('nome')
                ->criar();
            $db2->fechar();

            $db3 = new ferramentas($dbConfig);
            $db3->tabela('mi_protocolos')
                ->autoIncrementar()
                ->chavePrimaria()
                ->inteiro()
                ->inserirColuna('id')
                ->inteiro()
                ->inserirColuna('idcliente')
                ->inserirColuna('descricao')
                ->criar();
            $db3->fechar();

            $arquivo->criar(dirname($dbConfig['arquivo']) . '/criardb.txt');
        }
    } catch (SQLite3Exception $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo "Erro ao criar o banco de dados: " . $e->getMessage();
        exit;
    }
}
criarDB();
