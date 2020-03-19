<?php

    if(isset($_GET['h']) && !empty(['h'])){
        $h = addslashes($_GET['h']);

        require 'classes/usuarios/Usuario.php';
        require 'classes/usuarios/UsuarioDao.php';

        $usuario = new Usuarios();
        $ud = new UsuarioDao();

        $usuario->setId($h);
        $ud->updateActiveUser($usuario->getId());
        echo "<div class='alert alert-success'><h3>Cadastro cofirmado com sucesso.</h3></div></br>";
        echo "<a class='btn btn-primary' href='../login.php'>Acessar CvSoftware</a>";
    }

?>
