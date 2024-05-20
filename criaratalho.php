<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

$sFolder = '/home/' . get_current_user() . '/.local/share/applications/';
if (file_exists($sFolder)) {
    $sDesktop = "[Desktop Entry]\n
Version=" . file_get_contents(dirname(__FILE__) . '/version') . "\n
Name=MIProtoclo\n
Comment=Gera protocolos para envio e recebimento de documentos\n
Type=Application\n
Exec=" . dirname(__FILE__, 4) . "/miprotocolo\n
Icon=" . dirname(__FILE__, 2) . "/icon/miprotocolo.png\n
Categories=Utility;";

    $sCreateFile = file_put_contents($sFolder . '/miprotocolo.desktop', $sDesktop);
    if ($sCreateFile) {
        echo '<script>window.miapp.mensagem(\'MIProtocolo\', \'Atalho criado no menu iniciar!\', \'info\', false);window.location.assign(\'index.php\');</script>';
    } else {
        echo '<script>window.miapp.mensagem(\'MIProtocolo\', \'Não foi possível criar o atalho no menu iniciar!\', \'error\', false);window.location.assign(\'index.php\');</script>';
    }
} else {
    echo '<script>window.miapp.mensagem(\'MIProtocolo\', \'Não foi possível criar o atalho no menu iniciar!\', \'error\', false);window.location.assign(\'index.php\');</script>';
}
