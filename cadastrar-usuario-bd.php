<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['login']) && !empty($_POST['login'])
        && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])){

        require 'classes/usuarios/usuarios.class.php';
        require_once 'classes/usuarios/usuariosDao.class.php';

        $usuario = new Usuarios();
        $ud = new UsuarioDao();

        $usuario->setNome(addslashes($_POST['nome']));
        $usuario->setLogin(addslashes($_POST['login']));
        $usuario->setEmail(addslashes($_POST['email']));
        $usuario->setSenha(md5(addslashes($_POST['senha'])));
        $usuario->setIdHospital(addslashes($_POST['hospital']));
        $usuario->setTelefone(addslashes($_POST['telefone']));

        if($ud->addUsuario($usuario->getNome(), $usuario->getLogin(), $usuario->getEmail(), $usuario->getSenha(), $usuario->getIdHospital(), $usuario->getTelefone())==true){

            $id = $ud->lastInsertId();

            $idMd5 = md5($id);

            //Fazer aqui a montagem do e-mail do usuário a ser cadastrado.
            $_SESSION['msg'] = "Usuário cadastrado com sucesso.";
            header('Location: cadastrar-usuario.php');
        }else{
            $_SESSION['msg'] = "Erro ao cadastrar usuário.";
            header('Location: cadastrar-usuario.php');
        }

    }
    else{
        $_SESSION['msg'] = "Preencha todos os campos e tente realizar o registro novamente."; //Falta criar div para receber a msg na tela de cadastro.
        header('Location: cadastrar-usuario.php');
    }

?>
