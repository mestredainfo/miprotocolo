<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\arquivo;
use MISistema\app\funcoes;
use MISistema\sistema\servidor;

$arquivo = new arquivo();
$server = new servidor();
$script = new funcoes();

if ($arquivo->existe($server->pastaSistema() . '/.miprotocolo/dados/createdb.txt')) {
	$arquivo->excluir($server->pastaSistema() . '/.miprotocolo/dados/createdb.txt');
}

$script->redirecionar($server->dominio() . '/protocolos/list.php');
