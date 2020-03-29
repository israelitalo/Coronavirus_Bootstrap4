<?php
session_start();
if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require_once __DIR__ . '/vendor/autoload.php';

use Classes\Adm\Adm;
use Classes\Adm\AdmDao;

if(empty($_GET['id'])){
    ?>
    <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
    <?php
}

$adm = new Adm();
$admDao = new AdmDao();

$adm->setId(addslashes($_GET['id']));

if($admDao->excluirAdm($adm->getId()) == true){
    $_SESSION['msg'] = "Adm excluído com sucesso.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
    <?php
}else{
    $_SESSION['msg'] = "Erro ao tentar excluir Adm.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
    <?php
}

?>
