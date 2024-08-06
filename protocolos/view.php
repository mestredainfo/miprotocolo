<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\funcoes;
use MISistema\dados\consultar;
use MISistema\seguranca\post;
use MISistema\sistema\servidor;
use Mpdf\Mpdf;

$form = new post();
$server = new servidor();
$script = new funcoes();

include_once($server->pastaSistema() . '/includes.php');

function getEmpresa(): string
{
    global $dbConfig;

    $txtNome = '';

    $db1 = new consultar($dbConfig);
    $db1->tabela('mi_options')
        ->onde('id', 1)
        ->gerar();

    while ($row = $db1->vetores()) {
        $db1->valores($row);
        $txtNome = $db1->valor('nome');
    }

    $db1->fechar();

    return $txtNome;
}
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

$sEmpresa = getEmpresa();

$sHTML = '<style>td { font-size: 18px; }</style><table style="width:100%">';

$mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
$mpdf->SetTitle('Protocolos-' . time());
$mpdf->SetAuthor($sEmpresa);
$mpdf->SetCreator('MIProtocolo');
$mpdf->SetSubject('Protocolos gerados para o envio e recebimento de documentos');

$db1 = new consultar($dbConfig);

$db1->ativarPreparado()->tabela('mi_protocolos');

if ($form->solicitado()) {
    if (!empty($_POST['ids'])) {
        if (is_array($_POST['ids'])) {
            $sIDs = $_POST['ids'];

            foreach ($sIDs as $valor) {
                $db1->dentro()->ou()->onde('id', $valor);
            }
        }
    }
}

$db1->decrescente()->ordem('id')
    ->gerar();

$first = true;
$count = 1;
while ($row = $db1->vetores()) {
    $db1->valores($row);

    if ($count == 4) {
        $sHTML .= '</table>';
        $sHTML .= '<pagebreak>';
        $sHTML .= '<table style="width:100%">';

        $first = true;
        $count = 1;
    }

    if ($first == false) {
        $sHTML .= '<tr><td colspan="2">';
        $sHTML .= '<hr style="height:2px;margin-top:37px;margin-bottom:27px;">';
        $sHTML .= '</td></tr>';
    }

    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2" style="font-weight:bold;padding-top: 7px;font-size:22px;text-align:center;">' . $sEmpresa . '</td>';
    $sHTML .= '</tr>';

    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2" style="font-weight:bold;padding-top:7px;font-size:18px;text-align:center;">Protocolo</td>';
    $sHTML .= '</tr>';

    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2"><span style="font-weight:bold;">Cliente:</span> ' . getCliente($db1->valor('idcliente')) . '</td>';
    $sHTML .= '</tr>';
    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2" style="font-weight:bold;padding-top:7px;">Descrição:</td>';
    $sHTML .= '</tr>';
    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2" style="padding-top:3px;">' . stripslashes($db1->valor('descricao')) . '</td>';
    $sHTML .= '</tr>';


    $sHTML .= '<tr>';
    $sHTML .= '<td style="font-weight:bold;text-align:center;padding-top: 37px;">Data: _______/_______/_______</td>';
    $sHTML .= '<td style="font-weight:bold;text-align:center;padding-top: 37px;">Assinatura: _________________________________</td>';
    $sHTML .= '</tr>';

    $first = false;
    $count += 1;
}

$db1->fechar();

$sHTML .= '</table>';

$mpdf->WriteHTML($sHTML);

$mpdf->Output();
