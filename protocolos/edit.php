<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MIProtocolo\database\insert;
use MIProtocolo\database\select;
use MIProtocolo\database\update;

header("Content-Security-Policy: default-src 'self'");
header("Content-Security-Policy: script-src 'self' 'unsafe-inline' script.js");

$documentroot = dirname(__FILE__, 2);
include_once($documentroot . '/includes.php');

$txtID = CleanGET('id');
if (requestPOST()) {
    $txtIDCliente = CleanPOST('txtIDCliente');
    $txtDescricao = addslashes(CleanPOST('txtDescricao'));

    if (empty($txtID)) {
        $db2 = new insert($dbConfig, insert::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->insertValue('idcliente', $txtIDCliente)
            ->insertValue('descricao', $txtDescricao)
            ->insert();
        $db2->close();
    } else {
        $db2 = new update($dbConfig, update::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->insertValue('idcliente', $txtIDCliente)
            ->insertValue('descricao', $txtDescricao)
            ->where('id', $txtID)
            ->update();
        $db2->close();
    }

    redirect(servername() . '/index.php?c=1');
}

$txtIDCliente = '';
$txtDescricao = '';
if (!empty($txtID)) {
    $db1 = new select($dbConfig, select::sqlite3ReadOnly);
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
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <form name="frmEdit" method="post" action="edit.php?id=<?php echo $txtID; ?>">
        <div class="mb-3">
            <label for="txtIDCliente">Nome</label>
            <select id="txtIDCliente" name="txtIDCliente" class="form-control">
                <?php
                $cCliente = false;
                $db1 = new select($dbConfig, select::sqlite3ReadOnly);
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
            <textarea id="txtDescricao" name="txtDescricao" class="form-control" cols="7" rows="7"><?php echo $txtDescricao; ?></textarea>
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

    <script src="/bootstrap/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>