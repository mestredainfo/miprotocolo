<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\funcoes;
use MISistema\dados\consultar;
use MISistema\dados\remover;
use MISistema\seguranca\get;
use MISistema\sistema\servidor;

$form = new get();
$server = new servidor();
$script = new funcoes();

include_once($server->pastaSistema() . '/includes.php');

if ($form->existe('tipo') && $form->existe('id')) {
    if ($form->obter('tipo') == 'removeitem') {
        $sID = $form->obter('id', FILTER_SANITIZE_NUMBER_INT);

        $db2 = new remover($dbConfig);
        $db2->ativarPreparado()
            ->tabela('mi_protocolos')
            ->onde('id', $sID)
            ->gerar();
        $db2->fechar();

        $script->redirecionar($server->dominio());
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

            $db1 = new consultar($dbConfig);
            $db1->coluna('mi_protocolos.id as pid')
                ->coluna('mi_protocolos.idcliente as pidcliente')
                ->coluna('mi_clientes.id as cid')
                ->coluna('mi_clientes.nome as cnome')
                ->tabela('mi_protocolos')
                ->tabela('mi_clientes')
                ->onde('pidcliente', 'cid')
                ->decrescente()->ordem('pid')
                ->gerar();

            while ($row = $db1->vetores()) {
                $db1->valores($row);
                $cid = $db1->valor('pid');
                $cnome = $db1->valor('cnome');
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
            $db1->fechar();

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
            Veja como você pode apoiar este software, <a href="javascript:misistema.abrirURL('https://www.mestredainfo.com.br/p/apoie.html');">clique aqui</a>
        </strong>
    </div>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>

    <script>
        function removeItem(id) {
            misistema.confirmacao('Excluir Registro', 'Você deseja realmente remover esse registro?', 'question').then((result) => {
                if (!result) {
                    window.location.assign(`/protocolos/list.php?tipo=removeitem&id=${id}`);
                }
            });
        }
    </script>

</body>

</html>