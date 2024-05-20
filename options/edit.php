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

if (requestPOST()) {
    $txtNome = CleanPOST('txtNome');

    $db2 = new update($dbConfig, update::sqlite3ReadWrite);
    $db2->ativarPrepare()
        ->table('mi_options')
        ->insertValue('nome', $txtNome)
        ->where('id', 1)
        ->update();
    $db2->close();

    redirect(servername() . '/options/edit.php');
}

$txtNome = '';

$db1 = new select($dbConfig);
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
    $db2 = new insert($dbConfig, insert::sqlite3ReadWrite);
    $db2->table('mi_options')
        ->insertValue('nome', 'Nome da Empresa')
        ->insert();
    $db2->close();

    redirect('edit.php');
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