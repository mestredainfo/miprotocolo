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

if (miRequestPOST()) {
    $txtNome = miCleanPOST('txtNome');

    $db2 = new miDBUpdate($dbConfig, miDBUpdate::sqlite3ReadWrite);
    $db2->ativarPrepare()
        ->table('mi_options')
        ->insertValue('nome', $txtNome)
        ->where('id', 1)
        ->update();
    $db2->close();

    miRedirect(miServerName() . '/options/edit.php?tipo=atualizado');
}

$txtNome = '';

$db1 = new miDBSelect($dbConfig);
$db1->table('mi_options')
    ->where('id', 1)
    ->select();

$comRegistro = false;
while ($row = $db1->fetch()) {
    $db1->rows($row);

    if ($db1->row('nome') !== 'Nome da Empresa') {
        $txtNome = $db1->row('nome');
    }

    $comRegistro = true;
}

$db1->close();

if (!$comRegistro) {
    $db2 = new miDBInsert($dbConfig, miDBInsert::sqlite3ReadWrite);
    $db2->table('mi_options')
        ->insertValue('nome', 'Nome da Empresa')
        ->insert();
    $db2->close();

    miRedirect('edit.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro da Empresa - MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div style="margin-top:17px;font-size:27px;font-weight:bold;text-align:center;">
        Cadastro da Empresa
    </div>
    <?php if (!empty($_GET['tipo'])) { ?>
        <div class="alert alert-success">Salvo com sucesso!</div>
    <?php } ?>
    <form name="frmEdit" method="post" action="edit.php">
        <div class="mb-3">
            <label for="txtNome">Nome</label>
            <input id="txtNome" name="txtNome" type="text" value="<?php echo $txtNome; ?>" placeholder="Digite o nome da empresa aqui" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>