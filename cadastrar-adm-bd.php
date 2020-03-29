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

if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['login']) && !empty($_POST['login'])
    && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) {

    $adm = new Adm();
    $admDao = new AdmDao();

    $adm->setNome(addslashes(ucwords($_POST['nome'])));
    $adm->setLogin(addslashes($_POST['login']));
    $adm->setSenha(addslashes(md5($_POST['senha'])));
    $adm->setTelefone(addslashes($_POST['telefone']));
    $adm->setEmail(addslashes($_POST['email']));

    if($admDao->addAdm($adm->getNome(), $adm->getLogin(), $adm->getSenha(), $adm->getTelefone(), $adm->getEmail()) == true){
        $_SESSION['msg'] = "Adm cadastrado com sucesso.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
        <?php
    }else{
        $_SESSION['msg'] = "Erro ao cadastrar Adm.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
        <?php
    }

}
?>
