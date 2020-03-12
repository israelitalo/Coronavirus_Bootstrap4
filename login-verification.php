<?php
    session_start();
    require 'classes/usuarios/usuarios.class.php';
    require 'classes/usuarios/usuariosDao.class.php';
    require 'classes/adm/adm.class.php';
    require 'classes/adm/admDao.class.php';

    $usuario = new Usuarios();
    $ud = new UsuarioDao();
    $adm = new Adm();
    $admDao = new AdmDao();

    if(isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['senha']) && !empty($_POST['senha'])){
        $usuario->setLogin(addslashes($_POST['login']));
        $usuario->setSenha(addslashes(md5($_POST['senha'])));
        $adm->setLogin(addslashes($_POST['login']));
        $adm->setSenha(addslashes(md5($_POST['senha'])));

        /*$login = strip_tags(strtoupper(filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING)));

        if(is_string($login)){
            //
        }else{
            //
        }*/

        if($ud->login($usuario->getLogin(), $usuario->getSenha()) == true){
            $_SESSION['msg'] = "Bem vindo(a) ao CvSoftware";
            ?>
            <script type="text/javascript">window.location.href="index.php";</script>
            <?php
        }
        elseif($admDao->login($adm->getLogin(), $adm->getSenha()) == true){
            $_SESSION['msg'] = "Você é um Administrador";
            ?>
            <script type="text/javascript">window.location.href="index.php";</script>
            <?php
        }
        else{
            $_SESSION['msg'] = "Dados de acesso inválidos";
            ?>
            <script type="text/javascript">window.location.href="login.php";</script>
            <?php
        }
    }
?>
