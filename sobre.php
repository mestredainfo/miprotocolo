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
    <h1>Sobre o App</h1>
    <p>MIProtocolo <?php echo file_get_contents(dirname(__FILE__) . '/version'); ?></p>
    <p>Desenvolvido por: Murilo Gomes Julio</p>
    <p>Organização: Mestre da Info</p>
    <p>Site: <a href="javascript:window.externo.rodar('https://mestredainfo.wordpress.com');">mestredainfo.wordpress.com</a></p>

    <p>Copyright &copy; 2004-2024 Murilo Gomes Julio</p>

    <p>Licença: GPL-2.0-only</p>

    <hr class="border border-primary border-3 opacity-75">

    <h3>Recursos de Terceiros Utilizados</h3>

    <p><strong>ElectronJS:</strong> <a href="javascript:window.externo.rodar('https://www.electronjs.org');">electronjs.org</a></p>

    <p><strong>PHP:</strong> <a href="javascript:window.externo.rodar('https://www.php.net');">php.net</a></p>

    <p><strong>Bootstrap:</strong> <a href="javascript:window.externo.rodar('https://getbootstrap.com');">getbootstrap.com</a></p>

    <p><strong>TinyMCE:</strong> <a href="javascript:window.externo.rodar('https://github.com/tinymce/tinymce');">github.com/tinymce/tinymce</a></p>

    <p><strong>mPDF:</strong> <a href="javascript:window.externo.rodar('https://github.com/mpdf/mpdf');">github.com/mpdf/mpdf</a></p>

    <script src="/js/script.js"></script>
</body>

</html>