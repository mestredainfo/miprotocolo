<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\funcoes;
use MISistema\dados\atualizar;
use MISistema\dados\consultar;
use MISistema\dados\inserir;
use MISistema\seguranca\get;
use MISistema\seguranca\post;
use MISistema\sistema\ambiente;
use MISistema\sistema\servidor;

$env = new ambiente();
$script = new funcoes();
$server = new servidor();

include_once($env->pastaSistema() . '/includes.php');

$post = new post();
$get = new get();
$txtID = $get->obter('id');
if ($post->solicitado()) {
    $txtNome = $post->obter('txtNome');

    if (empty($txtID)) {
        $db2 = new inserir($dbConfig, inserir::LeituraEscrita);
        $db2->ativarPreparado()
            ->tabela('mi_clientes')
            ->inserirValor('nome', $txtNome)
            ->gerar();
        $db2->fechar();
    } else {
        $db2 = new atualizar($dbConfig, atualizar::LeituraEscrita);
        $db2->ativarPreparado()
            ->tabela('mi_clientes')
            ->inserirValor('nome', $txtNome)
            ->onde('id', $txtID)
            ->gerar();
        $db2->fechar();
    }

    $script->redirecionar($server->dominio() . '/clientes/list.php');
}

$txtNome = '';
if (!empty($txtID)) {
    $db1 = new consultar($dbConfig, consultar::SomenteLeitura);
    $db1->ativarPreparado()
        ->tabela('mi_clientes')
        ->onde('id', $txtID)
        ->gerar();

    while ($row = $db1->vetores()) {
        $db1->valores($row);
        $txtNome = $db1->valor('nome');
    }

    $db1->fechar();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $env->idioma(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar/Editar Cliente - MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div style="margin-top:17px;font-size:27px;font-weight:bold;text-align:center;">
        Adicionar/Editar Cliente
    </div>
    <form name="frmEdit" method="post" action="edit.php?id=<?php echo $txtID; ?>">
        <div class="mb-3">
            <label for="txtNome">Nome</label>
            <input id="txtNome" name="txtNome" type="text" value="<?php echo $txtNome; ?>" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>