<?php
    session_start();

    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require 'pages/header.php';
    require 'classes/hospitais/UnidadeHospitalar.php';
    require 'classes/hospitais/UnidadeHospitalarDao.php';

    $unidades = new UnidadeHospitalar();
    $ud = new UnidadeHospitalarDao();
    $unidades = $ud->getAllHospitais();

?>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de usuário</title>
</head>
    <section class="container">
        <div style="margin-top: 20px; margin-bottom: 20px">
            <h2><span class="badge badge-secondary">Cadastrar Usuários</span></h2>
        </div>
        <div class="col">
            <form class="form-group" method="POST" action="cadastrar-usuario-bd.php">

                <div class="form-group">
                    <label class="col-form-label-lg" for="nome">Nome</label>
                    <input class="form-control" autocomplete="off" type="text" name="nome" id="nomeUsuario" required placeholder="Nome do usuário">
                </div>

                <div class="row">
                    <div class="col">
                        <label class="col-form-label-lg" for="login">Login</label>
                        <input class="form-control" autocomplete="off" type="text" name="login" id="loginUsuario" required placeholder="Login do usuário">
                    </div>
                    <div class="col">
                        <label class="col-form-label-lg" for="hospital">Hospital</label>
                        <select class="form-control" name="hospital">
                            <option></option>
                            <?php  foreach ($unidades as $unidade): ?>
                                <option value="<?php echo $unidade['id']; ?>"><?php echo ucwords($unidade['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="col-form-label-lg" for="email">E-mail</label>
                        <input class="form-control" type="email" name="email" id="emailUsuario" required placeholder="E-mail do usuário">
                    </div>

                    <div class="col">
                        <label class="col-form-label-lg" for="senha">Senha</label>
                        <input class="form-control" autocomplete="off" type="password" name="senha" id="senhaUsuario" required placeholder="Senha do usuário">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input class="form-control" type="tel" name="telefone" id="TelefoneUsuario" required placeholder="Telefone do usuário">
                </div>

                <div class="form-group" style="margin-top: 20px">
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </form>
        </div>
    </section>
