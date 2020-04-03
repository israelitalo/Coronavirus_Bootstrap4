<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    include_once 'pages/header.php';
    include_once 'pages/navbar.php';

    use Classes\Adm\Adm;
    use Classes\Adm\AdmDao;

    $adms = new Adm() ;
    $admDao = new AdmDao();

    $qtPaginas = 6;
    $pg = 1;

    if(isset($_GET['p']) && !empty($_GET['p'])){
        $pg = addslashes($_GET['p']);
    }

    $p = ($pg - 1) * $qtPaginas;

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $countAdmComLike = $admDao->countAdmComLike($busca);
        $paginas = $countAdmComLike['total'] / $qtPaginas;
        $adms = $admDao->getAdmLike($busca, $p, $qtPaginas);
    }else{
        $countAdm = $admDao->countAdm();
        $paginas = $countAdm['total'] / $qtPaginas;
        $adms = $admDao->getAllAdmPaginacao($p, $qtPaginas);
    }

?>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Administradores</span></h2>
    </div>
    <?php
        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
            if ($msg == "Adm cadastrado com sucesso.") {
                ?>
                <div class="alert alert-success"><?php echo $msg ?></div>
                <?php
            } elseif ($msg == "Erro ao cadastrar Adm.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Adm alterado com sucesso.") {
                ?>
                <div class="alert alert-success"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Erro ao alterar Adm.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Adm excluído com sucesso.") {
                ?>
                <div class="alert alert-success"><?php echo $msg ?></div>
                <?php
            }elseif ($msg == "Erro ao tentar excluir Adm.") {
                ?>
                <div class="alert alert-danger"><?php echo $msg ?></div>
                <?php
            }
            unset($_SESSION['msg']);
        }
    ?>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-6">
            <a class="btn btn-success" href="cadastrar-adm.php" role="button" style="margin-right: 10px">Adicionar</a>
            <a class="btn btn-outline-secondary" href="imprimirAdm.php?busca=<?php echo (isset($_GET['busca']) && !empty($_GET['busca']))?$_GET['busca']:''; ?>" target="_blank" style="color: white"><img src="assets/images/icones/impressora.png"></a>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6 align-items-end">
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
            <caption>Administradores Cadastrados</caption>
            <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>login</th>
                <th>telefone</th>
                <th>E-mail</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($adms as $adm): ?>
                <tr>
                    <td style="width: 20%; padding-top: 20px"><?php echo ucwords($adm['nome']); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $adm['login']; ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $adm['telefone']; ?></td>
                    <td style="width: 30%; padding-top: 20px"><?php echo $adm['email']; ?></td>
                    <td class="text-center" style="width: 20%">
                        <a style="margin-right: 5px" class="btn btn-outline-warning" href="alterar-adm.php?id=<?php echo $adm['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" excluir-adm="Deseja excluir este adm?" href="excluir-adm.php?id=<?php echo $adm['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <ul class="pagination">
            <?php for($i=0;$i<$paginas;$i++): ?>
                <li class="page-item <?php echo ($_GET['p']-1 == $i)?'active':'' ;?>">
                    <a class="page-link" href="gerenciar-adm.php?<?php
                    $get = $_GET;//Aqui passa tudo que há no $_GET para a variável get.
                    $get['p'] = $i+1;
                    echo http_build_query($get);//Transforma todos os itens que há em $_GET em url.
                    ?>" ><?php echo $i+1; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</div>
<script type="text/javascript" src="modal-excluir-adm.js"></script>
