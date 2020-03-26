<?php
    session_start();
    unset($_SESSION['id_usuario'], $_SESSION['msg'], $_SESSION['id_adm'], $_SESSION['usuario_hospital'], $_SESSION['nomeUser']);
    header('Location: login.php');
    exit;
?>
