<?php

use MISistema\app\config;
use MISistema\app\criaratalho;

$config = new config();
$criaratalho = new criaratalho();

$criaratalho->nome('MIProtocolo')
    ->versao($config->obter('sistema', 'versao'))
    ->descricao('Gera protocolos para envio e recebimento de documentos.')
    ->catOffice()
    ->criar();
