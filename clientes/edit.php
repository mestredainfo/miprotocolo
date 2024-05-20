<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MIProtocolo\database\insert;
use MIProtocolo\database\select;
use MIProtocolo\database\update;

$documentroot = dirname(__FILE__, 2);
include_once($documentroot . '/includes.php');

$txtID = CleanGET('id');
if (requestPOST()) {
    $txtNome = CleanPOST('txtNome');

    if (empty($txtID)) {
        $db2 = new insert($dbConfig, insert::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_clientes')
            ->insertValue('nome', $txtNome)
            ->insert();
        $db2->close();
    } else {
        $db2 = new update($dbConfig, update::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_clientes')
            ->insertValue('nome', $txtNome)
            ->where('id', $txtID)
            ->update();
        $db2->close();
    }

    redirect(servername() . '/clientes/list.php');
}

$txtNome = '';
if (!empty($txtID)) {
    $db1 = new select($dbConfig, select::sqlite3ReadOnly);
    $db1->ativarPrepare()
        ->table('mi_clientes')
        ->where('id', $txtID)
        ->select();

    while ($row = $db1->fetch()) {
        $db1->rows($row);
        $txtNome = $db1->row('nome');
    }

    $db1->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar/Editar Clientes - MIProtocolo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <form name="frmEdit" method="post" action="edit.php?id=<?php echo $txtID; ?>">
        <div class="form-control">
            <label for="txtNome">Nome</label>
            <input id="txtNome" name="txtNome" type="text" value="<?php echo $txtNome; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <script src="/js/script.js"></script>
</body>

</html>