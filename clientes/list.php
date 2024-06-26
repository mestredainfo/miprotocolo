<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MIProtocolo\database\delete;
use MIProtocolo\database\select;

$documentroot = dirname(__FILE__, 2);
include_once($documentroot . '/includes.php');

if (!miEmptyGET('tipo') && !miEmptyGET('id')) {
    if (miCleanGET('tipo') == 'removeitem') {
        $sID = miCleanGET('id', FILTER_SANITIZE_NUMBER_INT);

        $db2 = new miDBDelete($dbConfig);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->where('idcliente', $sID)
            ->delete();
        $db2->close();

        $db2 = new miDBDelete($dbConfig);
        $db2->ativarPrepare()
            ->table('mi_clientes')
            ->where('id', $sID)
            ->delete();
        $db2->close();

        miRedirect(miServerName() . '/clientes/list.php');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div style="margin-top:17px;font-size:27px;font-weight:bold;text-align:center;">
        Gerenciar Clientes
    </div>
    <table class="table table-striped">
        <thead>
            <th style="text-align:left;">Nome do Cliente</th>
            <th></th>
        </thead>
        <tbody>
            <?php
            $count = 0;

            $db1 = new miDBSelect($dbConfig);
            $db1->table('mi_clientes')
                ->desc()->order('id')
                ->select();
            while ($row = $db1->fetch()) {
                $sid = empty($row['id']) ? 0 : $row['id'];
                $snome = empty($row['nome']) ? '' : $row['nome'];
            ?>
                <tr>
                    <td>
                        <a href="/clientes/edit.php?id=<?php echo $sid; ?>"><?php echo $snome; ?></a>
                    </td>
                    <td>
                        <button onclick="removeItem('<?php echo $sid; ?>')" class="btn btn-danger">Excluir</button>
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
    <hr>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>

    <script>
        function removeItem(id) {
            window.miapp.confirm('Excluir Registro', 'Você deseja realmente remover esse registro?', 'question', true).then((result) => {
                if (!result) {
                    window.location.assign(`<?php echo miServerName(); ?>/clientes/list.php?tipo=removeitem&id=${id}`);
                }
            });
        }
    </script>

</body>

</html>