<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\arquivo;
use MISistema\app\config;
use MISistema\app\sobre;
use MISistema\idioma\traduzir;
use MISistema\sistema\ambiente;

$env = new ambiente();
$traduzir = new traduzir();
?>
<!DOCTYPE html>
<html lang="<?php echo $env->idioma(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $traduzir->obter('Sobre o MIProtocolo'); ?></title>

    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php
    $arquivo = new arquivo();
    $config = new config();
    $sobre = new sobre();
    $sobre->nome('MIProtocolo')
        ->versao($config->obter('sistema', 'versao'))
        ->copyright('2004-2024', 'Murilo Gomes Julio')
        ->autor('Murilo Gomes Julio', 'Mestre da Info')
        ->paginainicial('https://www.mestredainfo.com.br')
        ->exibir($sobre->licenca('Bootstrap', 'Site: <a href="javascript:misistema.abrirURL(\'https://getbootstrap.com\');">getbootstrap.com</a>', $arquivo->abrir($env->pastaSistema() . '/plugins/bootstrap/LICENSE')));
    ?>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>