<?php
    if(empty($_SESSION['id_usuario']) && empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    if(isset($_SESSION['id_adm']) && !empty($_SESSION['id_adm'])){
        require 'classes/adm/Adm.php';
        require 'classes/adm/AdmDao.php';

        $adm = new Adm();
        $admDao = new AdmDao();
        $nome = $admDao->getNomeAdm(addslashes($_SESSION['id_adm']));
    }
    elseif(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])){
        require 'classes/usuarios/Usuario.php';
        require 'classes/usuarios/UsuarioDao.php';

        $usuario = new Usuarios();
        $ud = new UsuarioDao();
        $nome = $ud->getNomeUsuario(addslashes($_SESSION['id_usuario']));
    }

?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
    <title>CvSoftware Covid-19</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.bundle.min.js"></script>
</head>
    <div class="container-fluid" style="padding: 0">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #33b5e5">

            <a class="navbar-brand" style="color: whitesmoke" href="./">
                <img src="assets/images/logo/cvsoftware_logo.jpeg" width="30" height="30" class="d-inline-block align-top" style="border-radius: 50px; margin-right: 15px" alt="">
                CvSoftware
            </a>

            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarMenu">
                <div class="navbar-nav">
                    <?php if(isset($_SESSION['id_adm'])):?>
                        <a href="gerenciar-unidades.php" style="color: whitesmoke" class="nav-item nav-link">Gerenciar Hospitais</a>
                        <a href="gerenciar-usuarios.php" style="color: whitesmoke" class="nav-item nav-link">Gerenciar Usuários</a>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['id_usuario'])):?>
                        <a href="unidade-hospitalar.php" style="color: whitesmoke" class="nav-item nav-link modal_ajax">Unidade Hospitalar</a>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: whitesmoke" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pacientes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: #33b5e5">
                            <a href="gerenciar-pacientes.php" style="background-color: #33b5e5; color: whitesmoke" class="dropdown-item nav-link">Gerenciar Pacientes</a>
                            <a href="gerenciar-historico-pacientes.php" style="background-color: #33b5e5; color: whitesmoke" class="dropdown-item nav-link">Histórico</a>
                        </div>
                    </li>
                    <a href="./sair.php" class="nav-item nav-link" style="color: whitesmoke" >Sair</a>
                </div>
            </div>

            <!-- Se quiser adicionar outro item, deve ser aqui, para deixá-lo no lado direito.-->
            <span class="navbar-text" style="color: whitesmoke"><?php echo strtok(ucwords($nome), ' ');?></span>
        </nav>

        <!-- MODAL UNIDADE HOSPITALAR -->
        <div class="modal_bg">
            <div class="modal">
            </div>
        </div>
        <!-- FIM MODAL UNIDADE HOSPITALAR -->
    </div>

