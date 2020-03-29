<?php
session_start();

if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

include_once 'pages/header.php';
include_once 'pages/navbar.php';

require_once __DIR__ . '/vendor/autoload.php';

use Classes\Adm\Adm;
use Classes\Adm\AdmDao;

if(empty($_GET['id'])){
    ?>
    <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
    <?php
}

$info = new Adm();
$admDao = new AdmDao();

$info->setId(addslashes($_GET['id']));
$info = $admDao->getAdm($info->getId());

if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['login']) && !empty($_POST['login'])
    && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) {

    $adm = new Adm();

    $adm->setId(addslashes($_GET['id']));
    $adm->setNome(addslashes($_POST['nome']));
    $adm->setLogin(addslashes($_POST['login']));
    $adm->setSenha(addslashes(md5($_POST['senha'])));
    $adm->setTelefone(addslashes($_POST['telefone']));
    $adm->setEmail(addslashes($_POST['email']));

    if($admDao->alterarAdm($adm->getId(), $adm->getNome(), $adm->getLogin(), $adm->getSenha(), $adm->getTelefone(), $adm->getEmail()) == true){
        $_SESSION['msg'] = "Adm alterado com sucesso.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
        <?php
    }else{
        $_SESSION['msg'] = "Erro ao alterar Adm.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-adm.php";</script>
        <?php
    }

}

?>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Alterar Administrador</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST">

            <div class="form-group">
                <label class="col-form-label-lg" for="nome">Nome</label>
                <input class="form-control" value="<?php echo $info['nome']; ?>" autocomplete="off" type="text" name="nome" id="nomeAdm" required placeholder="Nome">
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="login">Login</label>
                    <input class="form-control" value="<?php echo $info['login']; ?>" autocomplete="off" type="text" name="login" id="loginAdm" required placeholder="Login">
                </div>
                <div class="col-6">
                    <label class="col-form-label-lg" for="senha">Senha</label>
                    <input class="form-control" autocomplete="off" type="password" name="senha" id="senhaAdm" required placeholder="Senha">
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="email">E-mail</label>
                    <input class="form-control" value="<?php echo $info['email']; ?>" type="email" name="email" id="emailUsuario" required placeholder="E-mail do adm">
                </div>

                <div class="col-6">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input class="form-control" value="<?php echo $info['telefone']; ?>" autocomplete="off" type="text" name="telefone" id="senhaUsuario" required placeholder="(81)99999999">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Alterar</button>
            </div>
        </form>
    </div>
</section>
