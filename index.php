<?php
    session_start();

    if(empty($_SESSION['id_usuario']) && empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }
    require_once 'pages/header.php';
?>
<div class="container">
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg == "Você é um Administrador"){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }
        elseif($msg == "Bem vindo(a) ao CvSoftware"){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
</div>
