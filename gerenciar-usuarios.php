<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require 'pages/header.php';
    require 'classes/usuarios/usuarios.class.php';
    require 'classes/usuarios/usuariosDao.class.php';

    $usuarios = new Usuarios();
    $ud = new UsuarioDao();

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $usuarios = $ud->getUsuarioLike($busca);
    }else{
        $usuarios = $ud->getUsuarios();
    }

?>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Usuários</span></h2>
    </div>
    <?php
        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
            if ($msg == "Usuário cadastrado com sucesso.") {
                ?>
                <div class="alert alert-success"><?php echo $msg ?></div>
                <?php
            } elseif ($msg == "Erro ao cadastrar usuário.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Usuário alterado com sucesso.") {
                ?>
                <div class="alert alert-success"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Erro ao alterar usuário.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Usuário excluído com sucesso.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }
            unset($_SESSION['msg']);
        }
    ?>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-usuario.php" role="button">Adicionar</a>
        </div>
        <div class="col-6 align-items-end">
            <!--Input de pesquisa da tabela abaixo-->
            <form class="form-group" method="GET">
                <div class="input-group mb-3">
                    <input name="busca" type="search" class="form-control mr-sm-2" placeholder="Busca" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col" style="margin-top: 15px">
        <table class="table table-hover table-light table-borderless table-responsive-lg">
            <caption>Usuários Cadastrados</caption>
            <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>Login</th>
                <th>Hospital</th>
                <th>Telefone</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td style="width: 25%; padding-top: 20px"><?php echo ucwords($usuario['nome']); ?></td>
                    <td style="width: 18%; padding-top: 20px"><?php echo $usuario['login']; ?></td>
                    <td style="width: 22%; padding-top: 20px"><?php echo ucwords($usuario['hospital']); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo ucwords($usuario['telefone']); ?></td>
                    <td class="text-center" style="width: 20%>">
                        <a class="btn btn-outline-warning" href="alterar-usuario.php?id=<?php echo $usuario['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" href="excluir-usuario.php?id=<?php echo $usuario['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" href="#"><img id="icone-lista" src="assets/images/icones/informacoes.png"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
