<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

header("Content-Security-Policy: default-src 'self'");
header("Content-Security-Policy: script-src 'self' 'unsafe-inline' script.js");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o MIProtocolo</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php echo miAboutApp('
    <p><strong>TinyMCE:</strong> <a href="javascript:window.miapp.openURL(\'https://github.com/tinymce/tinymce\');">github.com/tinymce/tinymce</a></p>
    <p><strong>mPDF:</strong> <a href="javascript:window.miapp.openURL(\'https://github.com/mpdf/mpdf\');">github.com/mpdf/mpdf</a></p>
', true); ?>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>