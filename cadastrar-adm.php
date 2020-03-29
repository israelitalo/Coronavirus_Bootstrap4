<?php
session_start();

if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

include_once 'pages/header.php';
include_once 'pages/navbar.php';

?>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Cadastrar Administrador</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST" action="cadastrar-adm-bd.php">

            <div class="form-group">
                <label class="col-form-label-lg" for="nome">Nome</label>
                <input class="form-control" autocomplete="off" type="text" name="nome" id="nomeAdm" required placeholder="Nome">
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="login">Login</label>
                    <input class="form-control" autocomplete="off" type="text" name="login" id="loginAdm" required placeholder="Login">
                </div>
                <div class="col-6">
                    <label class="col-form-label-lg" for="senha">Senha</label>
                    <input class="form-control" autocomplete="off" type="password" name="senha" id="senhaAdm" required placeholder="Senha">
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="email">E-mail</label>
                    <input class="form-control" type="email" name="email" id="emailUsuario" required placeholder="E-mail do adm">
                </div>

                <div class="col-6">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input class="form-control" autocomplete="off" type="text" name="telefone" id="senhaUsuario" required placeholder="(81)99999999">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Adicionar</button>
            </div>
        </form>
    </div>
</section>
