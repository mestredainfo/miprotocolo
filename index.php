<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MIProtocolo\database\delete;
use MIProtocolo\database\select;

$documentroot = dirname(__FILE__);

include_once($documentroot . '/includes.php');

if (empty($_GET['c'])) {
    include_once($documentroot . '/checkupdate.php');
    checkupdate();
    redirect('index.php', ['c' => 1]);
}

if (!emptyGET('tipo') && !emptyGET('id')) {
    if (CleanGET('tipo') == 'removeitem') {
        $sID = CleanGET('id', FILTER_SANITIZE_NUMBER_INT);

        $db2 = new delete($dbConfig);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->where('id', $sID)
            ->delete();
        $db2->close();

        redirect(servername(), ['c' => 1]);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIProtocolo</title>
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div style="margin-top:17px;font-size:27px;font-weight:bold;text-align:center;">
        Gerenciar Protocolos
    </div>
    <table class="table table-striped">
        <thead>
            <th>Nome do Cliente</th>
            <th></th>
        </thead>
        <tbody>
            <?php
            $count = 0;

            $db1 = new select($dbConfig);
            $db1->column('mi_protocolos.id as pid')
                ->column('mi_protocolos.idcliente as pidcliente')
                ->column('mi_clientes.id as cid')
                ->column('mi_clientes.nome as cnome')
                ->table('mi_protocolos')
                ->table('mi_clientes')
                ->where('pidcliente', 'cid')
                ->desc()->order('pid')
                ->select();

            while ($row = $db1->fetch()) {
                $db1->rows($row);
                $cid = $db1->row('pid');
                $cnome = $db1->row('cnome');
            ?>
                <tr>
                    <td>
                        <a href="/protocolos/edit.php?id=<?php echo $cid; ?>"><?php echo $cnome; ?></a>
                    </td>
                    <td>
                        <button onclick="removeItem('<?php echo $cid; ?>')" class="btn btn-danger">Excluir</button>
                    </td>
                </tr>
            <?php
                $count = 1;
            }
            $db1->close();

            if (empty($count)) { ?>
                <tr>
                    <td>Nenhum protocolo encontrado!</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <hr>
    <div style="text-align:center;margin-top:17px">
        <strong>
            Precisa de suporte? saiba mais clicando
            <a href="javascript:window.externo.rodar('https://mestredainfo.wordpress.com/assinantes/');">aqui</a>
        </strong>
    </div>
    <script src="/bootstrap/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>

    <script>
        function removeItem(id) {
            window.miapp.mensagem('Excluir Registro', 'Você deseja realmente remover esse registro?', 'question', true).then((result) => {
                if (!result) {
                    window.location.assign(`/?c=1&tipo=removeitem&id=${id}`);
                }
            });
        }
    </script>

</body>

</html>