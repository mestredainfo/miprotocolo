<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

$documentroot = dirname(__FILE__, 2);
include_once($documentroot . '/includes.php');

$txtID = miCleanGET('id');
if (miRequestPOST()) {
    $txtNome = miCleanPOST('txtNome');

    if (empty($txtID)) {
        $db2 = new miDBInsert($dbConfig, miDBInsert::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_clientes')
            ->insertValue('nome', $txtNome)
            ->insert();
        $db2->close();
    } else {
        $db2 = new miDBUpdate($dbConfig, miDBUpdate::sqlite3ReadWrite);
        $db2->ativarPrepare()
            ->table('mi_clientes')
            ->insertValue('nome', $txtNome)
            ->where('id', $txtID)
            ->update();
        $db2->close();
    }

    miRedirect(miServerName() . '/clientes/list.php');
}

$txtNome = '';
if (!empty($txtID)) {
    $db1 = new miDBSelect($dbConfig, miDBSelect::sqlite3ReadOnly);
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