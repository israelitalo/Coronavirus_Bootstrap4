<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    include 'pages/header.php';
    require 'classes/hospitais/UnidadeHospitalar.php';
    require 'classes/hospitais/UnidadeHospitalarDao.php';

    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    $qtPaginas = 10;
    $pg = 1;

    if(isset($_GET['p']) && !empty($_GET['p'])){
        $pg = addslashes($_GET['p']);
    }

    $p = ($pg - 1) * $qtPaginas;

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $countHospitaisComLike = $hd->countHospitaisComLike($busca);
        $paginas = $countHospitaisComLike['total'] / $qtPaginas;
        $hospitais = $hd->getHospitalLike($busca, $p, $qtPaginas);
    }else{
        $countHospitais = $hd->countHospitais();
        $paginas = $countHospitais['total'] / $qtPaginas;
        $hospitais = $hd->getAllHospitaisPaginacao($p, $qtPaginas);
    }

?>
<body>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Hospitais</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg == "Hospital alterado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Erro ao tentar alterar Hospital."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Hospital cadastrado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Erro ao tentar cadastrar hospital."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Preencha todos os campos e tente novamente."){
            ?>
            <div class="alert alert-warning"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "O hospital não pode ser excluído, pois está vinculádo ao usuário(a) "){
            ?>
            <div class="alert alert-warning"><?php echo $msg." ".ucwords($_SESSION['usuario_hospital']).".";?></div>
            <?php
        }elseif($msg == "Hospital excluído com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg'], $_SESSION['usuario_hospital']);
    }
    ?>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-hospital.php" role="button" style="margin-right: 10px">Adicionar</a>
            <a class="btn btn-outline-secondary" href="imprimirHospitais.php?busca=<?php echo (isset($_GET['busca']) && !empty($_GET['busca']))?$_GET['busca']:''; ?>" target="_blank" style="color: white"><img src="assets/images/icones/impressora.png"></a>
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
            <caption>Hospitais Cadastrados</caption>
            <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($hospitais as $hospital): ?>
                <?php
                $nomeUsuario = $hd->getUsuarioHospital($hospital['id']);
                ?>
                <tr>
                    <td style="width: 25%; padding-top: 20px"><?php echo ucwords($hospital['nome']); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $hospital['cnpj']; ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $hospital['telefone']; ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo ucwords($hospital['cidade']); ?></td>
                    <td style="width: 10%; padding-top: 20px"><?php echo ucwords($hospital['estado']); ?></td>
                    <td class="text-center" style="width: 20%>">
                        <a class="btn btn-outline-warning" href="alterar-hospital.php?id=<?php echo $hospital['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" excluir-hospital="Deseja excluir este hospital?" href="excluir-hospital.php?id=<?php echo $hospital['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" data-toggle="modal" data-target="#modalDetalhesPaciente<?php echo $hospital['id'] ;?>"><img id="icone-lista" src="assets/images/icones/informacoes.png"></a>
                    </td>
                </tr>
                <!-- Modal Detalhes do Hospital -->
                <div class="modal fade" id="modalDetalhesPaciente<?php echo $hospital['id'] ;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #c8cbcf">
                                <h5 class="modal-title" style="font-size: 20px" id="exampleModalLongTitle"><?php echo ucwords($hospital['nome']); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="background-color: #efefef">
                                <div class="h4">
                                    <p class="badge badge-success">Endereço</p>
                                    <p style="font-size: 18px"><?php echo ucwords($hospital['rua']).', ';?>
                                        <?php echo $hospital['numero'].', ';?>
                                        <?php echo ucwords($hospital['bairro']).', ';?>
                                        <?php echo $hospital['cep'].', ';?>
                                        <?php echo ucwords($hospital['cidade']).' -';?>
                                        <?php echo ucwords($hospital['estado']);?></p>
                                    <hr>
                                    <p class="badge badge-success">Usuário Responsável</p>
                                    <p style="font-size: 18px">
                                        <?php
                                        if($nomeUsuario['nome']!=''){
                                            echo utf8_decode(ucwords($nomeUsuario['nome']));
                                        }else{
                                            echo 'Aguardando usuário responsável.';
                                        }
                                        ?>
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="modal-footer" style="background-color: #efefef">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM Modal Detalhes do Hospital -->
            <?php endforeach; ?>
            </tbody>
        </table>
        <ul class="pagination">
            <?php for($i=0;$i<$paginas;$i++): ?>
                <li class="page-item">
                    <a class="page-link" href="gerenciar-unidades.php?<?php
                    $get = $_GET;//Aqui passa tudo que há no $_GET para a variável get.
                    $get['p'] = $i+1;
                    echo http_build_query($get);//Transforma todos os itens que há em $_GET em url.
                    ?>" ><?php echo $i+1; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <!--Tabela a ser usada com o DataTable
    <table id="listar-hospitais" class="display table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Name</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        </tfoot>
    </table>
    -->
</div>
</body>
<script type="text/javascript" src="modal-excluir-hospital.js"></script>
