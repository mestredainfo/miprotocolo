<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\funcoes;
use MISistema\dados\consultar;
use MISistema\dados\remover;
use MISistema\seguranca\get;
use MISistema\sistema\ambiente;
use MISistema\sistema\servidor;

$env = new ambiente();
$form = new get();
$script = new funcoes();
$server = new servidor();

include_once($env->pastaSistema() . '/includes.php');

if ($form->existe('tipo') && $form->existe('id')) {
    if ($form->obter('tipo') == 'removeitem') {
        $sID = $form->obter('id', FILTER_SANITIZE_NUMBER_INT);

        $db2 = new remover($dbConfig);
        $db2->ativarPreparado()
            ->tabela('mi_protocolos')
            ->onde('idcliente', $sID)
            ->gerar();
        $db2->fechar();

        $db2 = new remover($dbConfig);
        $db2->ativarPreparado()
            ->tabela('mi_clientes')
            ->onde('id', $sID)
            ->gerar();
        $db2->fechar();

        $script->redirecionar($server->dominio() . '/clientes/list.php');
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

            $db1 = new consultar($dbConfig);
            $db1->tabela('mi_clientes')
                ->decrescente()->ordem('id')
                ->gerar();
            while ($row = $db1->vetores()) {
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

            $db1->fechar();

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
            misistema.confirmacao('Excluir Registro', 'Você deseja realmente remover esse registro?', 'question').then((result) => {
                if (!result) {
                    window.location.assign(`<?php echo $server->dominio(); ?>/clientes/list.php?tipo=removeitem&id=${id}`);
                }
            });
        }
    </script>

</body>

</html>