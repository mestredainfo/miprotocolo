<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

include_once(miPathRoot() . '/includes.php');

if (!miEmptyGET('tipo') && !miEmptyGET('id')) {
    if (miCleanGET('tipo') == 'removeitem') {
        $sID = miCleanGET('id', FILTER_SANITIZE_NUMBER_INT);

        $db2 = new miDBDelete($dbConfig);
        $db2->ativarPrepare()
            ->table('mi_protocolos')
            ->where('id', $sID)
            ->delete();
        $db2->close();

        miRedirect(miServerName());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
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

            $db1 = new miDBSelect($dbConfig);
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
            <a href="javascript:window.miapp.openURL('https://mestredainfo.wordpress.com/assinantes/');">aqui</a>
        </strong>
    </div>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>

    <script>
        function removeItem(id) {
            window.miapp.confirm('Excluir Registro', 'Você deseja realmente remover esse registro?', 'question', true).then((result) => {
                if (!result) {
                    window.location.assign(`/protocolos/list.php?tipo=removeitem&id=${id}`);
                }
            });
        }
    </script>

</body>

</html>