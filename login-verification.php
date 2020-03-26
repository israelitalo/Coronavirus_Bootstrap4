<?php
    session_start();

    /*require 'Classes/Usuarios/Usuario.php';
    require 'Classes/Usuarios/UsuarioDao.php';
    require 'Classes/Adm/Adm.php';
    require 'Classes/Adm/AdmDao.php';*/

    require_once __DIR__ . "/vendor/autoload.php";

    use Classes\Adm\Adm;
    use Classes\Adm\AdmDao;
    use Classes\Usuarios\Usuarios;
    use Classes\Usuarios\UsuarioDao;

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
            //Caso o login de usuário seja válido:
            $usuario = new Usuarios();
            $ud = new UsuarioDao();
            $_SESSION['nomeUser'] = $ud->getNomeUsuario(addslashes($_SESSION['id_usuario']));
            $_SESSION['msg'] = "Bem vindo(a) ao CvSoftware";
            ?>
            <script type="text/javascript">window.location.href="index.php";</script>
            <?php
        }
        elseif($admDao->login($adm->getLogin(), $adm->getSenha()) == true){
            //Caso o login de ADM seja válido:
            $adm = new Adm();
            $admDao = new AdmDao();
            $_SESSION['nomeUser'] = $admDao->getNomeAdm(addslashes($_SESSION['id_adm']));
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
