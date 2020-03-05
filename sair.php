<?php
    session_start();
    unset($_SESSION['id_usuario'], $_SESSION['msg'], $_SESSION['id_adm']);
    header('Location: login.php');
    exit;
?>
