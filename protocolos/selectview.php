<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MIProtocolo\database\select;

$documentroot = dirname(__FILE__, 2);
include_once($documentroot . '/includes.php');

function getCliente(int $id): string
{
    global $dbConfig;

    $txtNome = '';

    $db1 = new select($dbConfig);
    $db1->table('mi_clientes')
        ->where('id', $id)
        ->select();

    while ($row = $db1->fetch()) {
        $db1->rows($row);
        $txtNome = $db1->row('nome');
    }

    $db1->close();

    return $txtNome;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Cliente(s) - MIProtocolo</title>
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline';" />
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <form name="frmSelectView" method="post" action="view.php">
        <table style="width:100%;">
            <thead>
                <th></th>
                <th style="text-align:left;">Selecionar Cliente(s)</th>
            </thead>
            <tbody>
                <?php
                $count = 0;

                $db1 = new select($dbConfig);
                $db1->column('id')
                    ->column('nome')
                    ->table('mi_protocolos')
                    ->order('id')
                    ->select();
                while ($row = $db1->fetch()) {
                    $sid = empty($row['id']) ? 0 : $row['id'];
                    $snome = empty($row['nome']) ? '' : getCliente($row['nome']);
                ?>
                    <tr>
                        <td class="td-checkbox">
                            <input name="ids[]" type="checkbox" value="<?php echo $row['id']; ?>">
                        </td>
                        <td>
                            <?php echo $snome; ?>
                        </td>
                    </tr>
                <?php
                    $count = 1;
                }

                $db1->close();

                if (empty($count)) { ?>
                    <tr>
                        <td>Nenhum cliente encontrado!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Gerar Relatório</button>
    </form>

    <script src="/js/script.js"></script>
</body>

</html>