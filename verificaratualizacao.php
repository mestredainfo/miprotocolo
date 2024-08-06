<?php

use MISistema\app\config;
use MISistema\app\atualizacao;
use MISistema\sistema\ambiente;

$env = new ambiente();
?>
<!DOCTYPE html>
<html lang="<?php echo $env->idioma(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Atualização</title>
</head>

<body>
    <?php
    $config = new config();
    $update = new atualizacao();
    
    $update->nome('MIProtocolo')
        ->versao($config->obter('sistema', 'versao'))
        ->url('https://www.mestredainfo.com.br/2024/07/miprotocolo.html')
        ->exibir()
        ->verificar();
    ?>
</body>

</html>