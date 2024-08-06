<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\funcoes;
use MISistema\dados\consultar;
use MISistema\seguranca\post;
use MISistema\sistema\servidor;

$form = new post();
$server = new servidor();
$script = new funcoes();

include_once($server->pastaSistema() . '/includes.php');

function getCliente(int $id): string
{
    global $dbConfig;

    $txtNome = '';

    $db1 = new consultar($dbConfig);
    $db1->tabela('mi_clientes')
        ->onde('id', $id)
        ->gerar();

    while ($row = $db1->vetores()) {
        $db1->valores($row);
        $txtNome = $db1->valor('nome');
    }

    $db1->fechar();

    return $txtNome;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Cliente(s) - MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
<div style="margin-top:17px;font-size:27px;font-weight:bold;text-align:center;">
        Selecionar Protocolo(s)
    </div>
    <form name="frmSelectView" method="post" action="view.php">
        <table class="table table-striped">
            <thead>
                <th style="text-align:left;">Selecionar Cliente(s)</th>
            </thead>
            <tbody>
                <?php
                $count = 0;

                $db1 = new consultar($dbConfig);
                $db1->coluna('id')
                    ->coluna('idcliente')
                    ->tabela('mi_protocolos')
                    ->ordem('id')
                    ->gerar();
                while ($row = $db1->vetores()) {
                    $sid = empty($row['id']) ? 0 : $row['id'];
                    $sidcliente = empty($row['idcliente']) ? '' : getCliente($row['idcliente']);
                ?>
                    <tr>
                        <td class="td-checkbox">
                            <div class="form-check">
                                <input id="ids<?php echo $sid; ?>" name="ids[]" type="checkbox" value="<?php echo $row['id']; ?>" class="form-check-input">
                                <label for="ids<?php echo $sid; ?>"><?php echo $sidcliente; ?></label>
                            </div>
                        </td>
                    </tr>
                <?php
                    $count = 1;
                }

                $db1->fechar();

                if (empty($count)) { ?>
                    <tr>
                        <td>Nenhum cliente encontrado!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Gerar Relatório</button>
    </form>

    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>