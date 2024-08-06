<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\funcoes;
use MISistema\dados\atualizar;
use MISistema\dados\consultar;
use MISistema\dados\inserir;
use MISistema\seguranca\get;
use MISistema\seguranca\post;
use MISistema\sistema\servidor;

$server = new servidor();
$form = new post();
$get = new get();
$script = new funcoes();

include_once($server->pastaSistema() . '/includes.php');

if ($form->solicitado()) {
    $txtNome = $form->obter('txtNome');

    $db2 = new atualizar($dbConfig, atualizar::LeituraEscrita);
    $db2->ativarPreparado()
        ->tabela('mi_options')
        ->inserirValor('nome', $txtNome)
        ->onde('id', 1)
        ->gerar();
    $db2->fechar();

    $script->redirecionar($server->dominio() . '/options/edit.php?tipo=atualizado');
}

$txtNome = '';

$db1 = new consultar($dbConfig, consultar::SomenteLeitura);
$db1->tabela('mi_options')
    ->onde('id', 1)
    ->gerar();

$comRegistro = false;
while ($row = $db1->vetores()) {
    $db1->valores($row);

    if ($db1->valor('nome') !== 'Nome da Empresa') {
        $txtNome = $db1->valor('nome');
    }

    $comRegistro = true;
}

$db1->fechar();

if (!$comRegistro) {
    $db2 = new inserir($dbConfig, inserir::LeituraEscrita);
    $db2->tabela('mi_options')
        ->inserirValor('nome', 'Nome da Empresa')
        ->gerar();
    $db2->fechar();

    $script->redirecionar('edit.php');
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
    <?php if ($get->existe('tipo')) { ?>
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