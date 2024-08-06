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
use MISistema\sistema\servidor;

$post = new post();
$get = new get();
$server = new servidor();
$script = new funcoes();

include_once($server->pastaSistema() . '/includes.php');

$txtID = $get->obter('id');
if ($post->solicitado()) {
    $txtIDCliente = $post->obter('txtIDCliente');
    $txtDescricao = addslashes($post->obter('txtDescricao'));

    if (empty($txtID)) {
        $db2 = new inserir($dbConfig, inserir::LeituraEscrita);
        $db2->ativarPreparado()
            ->tabela('mi_protocolos')
            ->inserirValor('idcliente', $txtIDCliente)
            ->inserirValor('descricao', $txtDescricao)
            ->gerar();
        $db2->fechar();
    } else {
        $db2 = new atualizar($dbConfig, atualizar::LeituraEscrita);
        $db2->ativarPreparado()
            ->tabela('mi_protocolos')
            ->inserirValor('idcliente', $txtIDCliente)
            ->inserirValor('descricao', $txtDescricao)
            ->onde('id', $txtID)
            ->gerar();
        $db2->fechar();
    }

    $script->redirecionar($server->dominio() . '/protocolos/list.php?c=1');
}

$txtIDCliente = '';
$txtDescricao = '';
if (!empty($txtID)) {
    $db1 = new consultar($dbConfig, consultar::SomenteLeitura);
    $db1->ativarPreparado()
        ->tabela('mi_protocolos')
        ->onde('id', $txtID)
        ->gerar();

    while ($row = $db1->vetores()) {
        $db1->valores($row);
        $txtIDCliente = $db1->valor('idcliente');
        $txtDescricao = stripslashes($db1->valor('descricao'));
    }

    $db1->fechar();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar/Editar Protocolos - MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
<div style="margin-top:17px;font-size:27px;font-weight:bold;text-align:center;">
        Adicionar/Editar Protocolo
    </div>
    <form name="frmEdit" method="post" action="edit.php?id=<?php echo $txtID; ?>">
        <div class="mb-3">
            <label for="txtIDCliente">Nome</label>
            <select id="txtIDCliente" name="txtIDCliente" class="form-control">
                <?php
                $cCliente = false;
                $db1 = new consultar($dbConfig, consultar::SomenteLeitura);
                $db1->tabela('mi_clientes')
                    ->ordem('nome')
                    ->gerar();

                while ($row = $db1->vetores()) {
                    $db1->valores($row);

                    echo '<option value="' . $db1->valor('id') . '"';
                    if ($db1->valor('id') == $txtIDCliente) {
                        echo ' selected="selected"';
                    }

                    echo '>' . $db1->valor('nome') . '</option>';

                    $cCliente = true;
                }

                $db1->fechar();
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtDescricao">Descrição</label>
            <textarea id="txtDescricao" name="txtDescricao" class="form-control"><?php echo $txtDescricao; ?></textarea>
        </div>

        <?php if ($cCliente) { ?>
            <button type="submit" class="btn btn-primary">Salvar</button>
        <?php } else { ?>
            <div style="font-weight: bold;text-align:center;">
                Adicione um cliente para criar um protocolo.<br>
                Clique no menu Clientes > Adicionar.
            </div>
        <?php } ?>
    </form>

    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/plugins/tinymce/tinymce.min.js"></script>
    <script src="/js/script.js"></script>

    <script>
        tinymce.init({
            selector: 'textarea',
            language: "pt_BR",
            plugins: '',
            toolbar: 'bold italic underline | cut copy paste selectall',
            menubar: false,
            height: '300px',
            statusbar: false,
            branding: false,
            license_key: 'gpl'
        });
    </script>
</body>

</html>