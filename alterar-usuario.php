<?php
    session_start();

    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require 'pages/header.php';
    require 'classes/hospitais/unidadeHospitalar.class.php';
    require 'classes/hospitais/unidadeHospitalarDao.class.php';
    require 'classes/usuarios/usuarios.class.php';
    require 'classes/usuarios/usuariosDao.class.php';

    $unidades = new UnidadeHospitalar();
    $ud = new UnidadeHospitalarDao();
    $unidades = $ud->getAllHospitais();

    if(empty($_GET['id'])){
        ?>
        <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
        <?php
    }

    $info = new Usuarios();
    $info->setId(addslashes($_GET['id']));
    $userDao = new UsuarioDao();

    $info = $userDao->getUsuario($info->getId());

    if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['login']) && !empty($_POST['login'])
        && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])){

        $usuario = new Usuarios();

        $usuario->setId(addslashes($_GET['id']));
        $usuario->setIdHospital(addslashes($_POST['hospital']));
        $usuario->setNome(addslashes($_POST['nome']));
        $usuario->setLogin(addslashes($_POST['login']));
        $usuario->setEmail(addslashes($_POST['email']));
        $usuario->setTelefone(addslashes($_POST['telefone']));
        $usuario->setSenha(md5(addslashes($_POST['senha'])));

        if($userDao->alterarUsuario($usuario->getId(), $usuario->getIdHospital(), $usuario->getNome(), $usuario->getLogin(),
            $usuario->getEmail(), $usuario->getTelefone(), $usuario->getSenha()) ==true){

        }
    }

?>
<head>
    <meta charset="UTF-8">
    <title>Alterar de usuário</title>
</head>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Alterar Usuário</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST">

            <div class="form-group">
                <label class="col-form-label-lg" for="nome">Nome</label>
                <input class="form-control" value="<?php echo ucwords($info['nome']); ?>" autocomplete="off" type="text" name="nome" id="nomeUsuario" required placeholder="Nome do usuário">
            </div>

            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="login">Login</label>
                    <input class="form-control" value="<?php echo $info['login']; ?>" autocomplete="off" type="text" name="login" id="loginUsuario" required placeholder="Login do usuário">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="hospital">Hospital</label>
                    <select class="form-control" name="hospital">
                        <option></option>
                        <?php  foreach ($unidades as $unidade): ?>
                            <option value="<?php echo $unidade['id']; ?>" <?php echo ($unidade['id']==$info['id_hospital'])?'selected="selected"':'' ; ?> ><?php echo ucwords($unidade['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="email">E-mail</label>
                    <input class="form-control" value="<?php echo $info['email']; ?>" type="email" name="email" id="emailUsuario" required placeholder="E-mail do usuário">
                </div>

                <div class="col">
                    <label class="col-form-label-lg" for="senha">Senha</label>
                    <input class="form-control" autocomplete="off" type="password" name="senha" id="senhaUsuario" required placeholder="Senha do usuário">
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label-lg" for="telefone">Telefone</label>
                <input class="form-control" type="tel" value="<?php echo $info['telefone']; ?>" name="telefone" id="TelefoneUsuario" required placeholder="Telefone do usuário">
            </div>

            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Adicionar</button>
            </div>
        </form>
    </div>
</section>

