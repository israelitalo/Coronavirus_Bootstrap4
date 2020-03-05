<?php
    session_start();

    if(empty($_SESSION['id_usuario']) && empty($_SESSION['id_adm'])){
        require 'sair.php';
    }
    require_once 'pages/header.php';
?>
<div class="container">

</div>
