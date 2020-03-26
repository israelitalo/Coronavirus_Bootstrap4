<?php
session_start();
if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require_once __DIR__ . '/vendor/autoload.php';

use Classes\Usuarios\Usuarios;
use Classes\Usuarios\UsuarioDao;

if(empty($_GET['id'])){
    ?>
    <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
    <?php
}

/*require 'Classes/Usuarios/Usuarios.php';
require 'Classes/Usuarios/UsuarioDao.php';*/

$usuario = new Usuarios();
$ud = new UsuarioDao();

$usuario->setId(addslashes($_GET['id']));

if($ud->excluirUsuario($usuario->getId()) == true){
    $_SESSION['msg'] = "Usuário excluído com sucesso.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
    <?php
}else{
    $_SESSION['msg'] = "Erro ao tentar excluir usuário.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
    <?php
}

?>
