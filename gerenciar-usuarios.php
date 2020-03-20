<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require 'pages/header.php';
    require 'classes/usuarios/Usuario.php';
    require 'classes/usuarios/UsuarioDao.php';

    $usuarios = new Usuarios();
    $ud = new UsuarioDao();

    $qtPaginas = 5;
    $pg = 1;

    if(isset($_GET['p']) && !empty($_GET['p'])){
        $pg = addslashes($_GET['p']);
    }

    $p = ($pg - 1) * $qtPaginas;

if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $countUsuariosComLike = $ud->countUsuariosComLike($busca);
        $paginas = $countUsuariosComLike['total'] / $qtPaginas;
        $usuarios = $ud->getUsuarioLike($busca, $p, $qtPaginas);
    }else{
        $countUsuarios = $ud->countUsuarios();
        $paginas = $countUsuarios['total'] / $qtPaginas;
        $usuarios = $ud->getAllUsuariosPaginacao($p, $qtPaginas);
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
                <div class="alert alert-success"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Erro ao tentar excluir usuário.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }
            unset($_SESSION['msg']);
        }
    ?>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-usuario.php" role="button" style="margin-right: 10px">Adicionar</a>
            <a class="btn btn-outline-secondary" href="imprimirUsuario.php?busca=<?php echo (isset($_GET['busca']) && !empty($_GET['busca']))?$_GET['busca']:''; ?>" target="_blank" style="color: white"><img src="assets/images/icones/impressora.png"></a>
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
                <th>Hospital</th>
                <th>Status</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td style="width: 35%; padding-top: 20px"><?php echo ucwords($usuario['nome']); ?></td>
                    <td style="width: 30%; padding-top: 20px"><?php echo ucwords($usuario['hospital']); ?></td>
                    <td style="width: 15%; padding-top: 20px">
                        <?php
                            if($usuario['ativo']==1){
                                echo "Ativo";
                            }else{
                                echo "Inativo";
                            }
                        ?>
                    </td>
                    <td class="text-center" style="width: 20%>">
                        <a class="btn btn-outline-warning" href="alterar-usuario.php?id=<?php echo $usuario['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" excluir-usuario="Deseja excluir este usuario?" href="excluir-usuario.php?id=<?php echo $usuario['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" data-toggle="modal" data-target="#modalDetalhesUsuario<?php echo $usuario['id'] ;?>"><img id="icone-lista" src="assets/images/icones/informacoes.png"></a>
                    </td>
                </tr>
                <!-- Modal Detalhes do Usuário -->
                <div class="modal fade" id="modalDetalhesUsuario<?php echo $usuario['id'] ;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #c8cbcf">
                                <h5 class="modal-title" style="font-size: 20px" id="exampleModalLongTitle"><?php echo ucwords($usuario['nome']); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="background-color: #efefef">
                                <div class="h4">
                                    <p class="badge badge-success">E-mail</p>
                                    <p style="font-size: 18px"><?php echo $usuario['email'];?></p>
                                    <hr>
                                    <p class="badge badge-success">Telefone</p>
                                    <p style="font-size: 18px"><?php echo $usuario['telefone'];?></p>
                                    <hr>
                                </div>
                            </div>
                            <div class="modal-footer" style="background-color: #efefef">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM Modal Detalhes do Usuário -->
            <?php endforeach; ?>
            </tbody>
        </table>
        <ul class="pagination">
            <?php for($i=0;$i<$paginas;$i++): ?>
                <li class="page-item">
                    <a class="page-link" href="gerenciar-usuarios.php?<?php
                    $get = $_GET;//Aqui passa tudo que há no $_GET para a variável get.
                    $get['p'] = $i+1;
                    echo http_build_query($get);//Transforma todos os itens que há em $_GET em url.
                    ?>" ><?php echo $i+1; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</div>
<script type="text/javascript" src="modal-excluir-usuario.js"></script>
