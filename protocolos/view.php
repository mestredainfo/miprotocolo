<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MIProtocolo\database\select;
use Mpdf\Mpdf;

$documentroot = dirname(__FILE__, 2);
include_once($documentroot . '/includes.php');

function getEmpresa(): string
{
    global $dbConfig;

    $txtNome = '';

    $db1 = new select($dbConfig);
    $db1->table('mi_options')
        ->where('id', 1)
        ->select();

    while ($row = $db1->fetch()) {
        $db1->rows($row);
        $txtNome = $db1->row('nome');
    }

    $db1->close();

    return $txtNome;
}
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

$sEmpresa = getEmpresa();

$sHTML = '<style>td { font-size: 18px; }</style><table style="width:100%">';

$mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
$mpdf->SetTitle('Protocolos-' . time());
$mpdf->SetAuthor($sEmpresa);
$mpdf->SetCreator('MIProtocolo');
$mpdf->SetSubject('Protocolos gerados para o envio e recebimento de documentos');

$db1 = new select($dbConfig);

$db1->ativarPrepare()->table('mi_protocolos');

if (requestPOST()) {
    if (is_array($_POST['ids'])) {
        $sIDs = $_POST['ids'];

        foreach ($sIDs as $valor) {
            $db1->in()->or()->where('id', $valor);
        }
    }
}
// if (!emptyPOST('ids')) {
//     if (is_array($_POST['ids'])) {
//         $sIDs = implode(',',$_POST['ids']);

//         $db1->like()->where('id', $sIDs);
//     }

//     exit;
// }

$db1->desc()->order('id')
    ->select();

$first = true;
$count = 1;
while ($row = $db1->fetch()) {
    $db1->rows($row);

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
    $sHTML .= '<td colspan="2"><span style="font-weight:bold;">Cliente:</span> ' . getCliente($db1->row('nome')) . '</td>';
    $sHTML .= '</tr>';
    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2" style="font-weight:bold;padding-top:7px;">Descrição:</td>';
    $sHTML .= '</tr>';
    $sHTML .= '<tr>';
    $sHTML .= '<td colspan="2" style="padding-top:3px;">' . nl2br(stripslashes($db1->row('descricao'))) . '</td>';
    $sHTML .= '</tr>';


    $sHTML .= '<tr>';
    $sHTML .= '<td style="font-weight:bold;text-align:center;padding-top: 37px;">Data: _______/_______/_______</td>';
    $sHTML .= '<td style="font-weight:bold;text-align:center;padding-top: 37px;">Assinatura: _________________________________</td>';
    $sHTML .= '</tr>';

    $first = false;
    $count += 1;
}

$db1->close();

$sHTML .= '</table>';

$mpdf->WriteHTML($sHTML);

$mpdf->Output();
