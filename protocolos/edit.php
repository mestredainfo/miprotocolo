<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

header("Content-Security-Policy: default-src 'self'");
header("Content-Security-Policy: script-src 'self' 'unsafe-inline' script.js");

include_once(miPathRoot() . '/includes.php');

$txtID = miCleanGET('id');
if (miRequestPOST()) {
    $txtIDCliente = miCleanPOST('txtIDCliente');
    $txtDescricao = addslashes(miCleanPOST('txtDescricao'));

    if (empty($txtID)) {
        $db2 = new miDBInsert($dbConfig, miDBInsert::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->insertValue('idcliente', $txtIDCliente)
            ->insertValue('descricao', $txtDescricao)
            ->insert();
        $db2->close();
    } else {
        $db2 = new miDBUpdate($dbConfig, miDBUpdate::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->insertValue('idcliente', $txtIDCliente)
            ->insertValue('descricao', $txtDescricao)
            ->where('id', $txtID)
            ->update();
        $db2->close();
    }

    miRedirect(miServerName() . '/protocolos/list.php?c=1');
}

$txtIDCliente = '';
$txtDescricao = '';
if (!empty($txtID)) {
    $db1 = new miDBSelect($dbConfig, miDBSelect::sqlite3ReadOnly);
    $db1->ativarPrepare()
        ->table('mi_protocolos')
        ->where('id', $txtID)
        ->select();

    while ($row = $db1->fetch()) {
        $db1->rows($row);
        $txtIDCliente = $db1->row('idcliente');
        $txtDescricao = stripslashes($db1->row('descricao'));
    }

    $db1->close();
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
                $db1 = new miDBSelect($dbConfig, miDBSelect::sqlite3ReadOnly);
                $db1->table('mi_clientes')
                    ->order('nome')
                    ->select();

                while ($row = $db1->fetch()) {
                    $db1->rows($row);

                    echo '<option value="' . $db1->row('id') . '"';
                    if ($db1->row('id') == $txtIDCliente) {
                        echo ' selected="selected"';
                    }

                    echo '>' . $db1->row('nome') . '</option>';

                    $cCliente = true;
                }

                $db1->close();
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