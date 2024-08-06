<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\atualizacao;
use MISistema\app\config;
use MISistema\app\funcoes;

$config = new config();
$update = new atualizacao();
$update->nome('MIProtocolo')
    ->versao($config->obter('sistema', 'versao'))
    ->url('https://www.mestredainfo.com.br/2024/07/miprotocolo.html')
    ->verificar();

$script = new funcoes();
$script->redirecionar('protocolos/list.php');
