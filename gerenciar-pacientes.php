<?php
    session_start();
    if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require 'pages/header.php';
    require 'classes/pacientes/paciente.class.php';
    require 'classes/pacientes/pacienteDao.class.php';

    $pd = new PacienteDao();
    $pacientes = new Paciente();

    $qtPaginas = 10;
    $pg = 1;

    if(isset($_GET['p']) && !empty($_GET['p'])){
        $pg = addslashes($_GET['p']);
    }

    $p = ($pg - 1) * $qtPaginas;

    if(isset($_SESSION['id_adm'])){
        $countPacientes = $pd->countPacientes();
        $paginas = $countPacientes['total'] / $qtPaginas;
        $pacientes = $pd->getAllPacientesPaginacao($p, $qtPaginas);
    }elseif(isset($_SESSION['id_usuario'])){
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $countPacientesUsuario = $pd->countPacientesUsuario($idUsuario);
        $paginas = $countPacientesUsuario['total'] / $qtPaginas;
        $pacientes = $pd->getAllPacienteForUserLogado($idUsuario, $p, $qtPaginas);
    }

    if(isset($_GET['busca']) && $_GET['busca'] != ''){
        $busca = addslashes($_GET['busca']);
        if(isset($_SESSION['id_adm'])){
            $countPacientesComLike = $pd->countPacientesComLike($busca);
            $paginas = $countPacientesComLike['total'] / $qtPaginas;
            $pacientes = $pd->getPacienteLike($busca, $p, $qtPaginas);

        }elseif(isset($_SESSION['id_usuario'])){
            $countPacientesUsuarioLike = $pd->countPacientesUsuarioLike($idUsuario, $busca);
            $paginas = $countPacientesUsuarioLike['total'] / $qtPaginas;
            $pacientes = $pd->getPacienteLikeForUserLogado($busca, $idUsuario, $p, $qtPaginas);

        }
    }

?>
<script>
    /*$(document).ready(function() {
        $('#listar-hospitais').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "tabela-empresas.php",
                "type": "POST"
            }
        });
    });*/
</script>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Pacientes</span></h2>
    </div>
    <?php
        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
            $msg = $_SESSION['msg'];
            if($msg == "Paciente alterado com sucesso."){
                ?>
                <div class="alert alert-success"><?php echo $msg?></div>
                <?php
            }
            elseif($msg == "Erro ao tentar alterar paciente."){
                ?>
                <div class="alert alert-danger"><?php echo $msg?></div>
                <?php
            }
            elseif($msg == "Paciente excluído(a) com sucesso."){
                ?>
                <div class="alert alert-success"><?php echo $msg?></div>
                <?php
            }
            elseif($msg == "O paciente não pode ser excluído, pois está vinculádo a um histórico de paciente."){
                ?>
                <div class="alert alert-warning"><?php echo $msg?></div>
                <?php
            }elseif($msg == "Erro ao cadastrar paciente."){
                ?>
                <div class="alert alert-danger"><?php echo $msg;?></div>
                <?php
            }elseif($msg == "Preencha todos os campos e tente realizar o registro novamente."){
                ?>
                <div class="alert alert-warning"><?php echo $msg;?></div>
                <?php
            }elseif($msg == "Paciente cadastrado com sucesso."){
                ?>
                <div class="alert alert-success"><?php echo $msg;?></div>
                <?php
            }
            unset($_SESSION['msg']);
        }
    ?>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-paciente.php" role="button">Adicionar</a>
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
            <caption>Pacientes Cadastrados</caption>
            <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>Hospital</th>
                <th>CPF</th>
                <th>Gênero</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pacientes as $paciente): ?>
                <?php
                    if($paciente['vida']==2){
                        require_once 'classes/historicoPaciente/historicoPacientes.class.php';
                        require_once 'classes/historicoPaciente/historicoPacientesDao.class.php';

                        $historico = new HistoricoPaciente();
                        $hd = new HistoricoPacienteDao();
                        $dataObito = $hd->getDataObitoPaciente($paciente['id']);
                    }
                ?>
                <tr>
                    <td style="width: 25%; padding-top: 20px">
                        <span style="font-size: 16px" <?php echo ($paciente['vida'] == 2)?'class="badge badge-danger"':''; ?> >
                            <?php echo ucwords($paciente['nome']);?>
                        </span>
                    </td>
                    <td style="width: 22%; padding-top: 20px"><?php echo ucwords($paciente['hospital']); ?></td>
                    <td style="width: 18%; padding-top: 20px"><?php echo $paciente['cpf']; ?></td>
                    <td style="width: 12%; padding-top: 20px">
                        <?php
                            if($paciente['sexo']=='m'):echo 'Masculino'; else:echo 'Feminino'; endif;
                        ?>
                    </td>
                    <td class="text-center" style="width: 20%>">
                        <a class="btn btn-outline-warning" href="alterar-paciente.php?id=<?php echo $paciente['id']; ?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" excluir-paciente="Deseja excluir este paciente?" href="excluir-paciente.php?id=<?php echo $paciente['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" data-toggle="modal" data-target="#modalDetalhesPaciente<?php echo $paciente['id'] ;?>"><img id="icone-lista" src="assets/images/icones/informacoes.png"></a>
                    </td>
                </tr>
                <!-- Modal Detalhes de Paciente -->
                <div class="modal fade" id="modalDetalhesPaciente<?php echo $paciente['id'] ;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #c8cbcf">
                                <h5 class="modal-title" style="font-size: 20px" id="exampleModalLongTitle"><?php echo ucwords($paciente['nome']); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="background-color: #efefef">
                                <div class="h4">
                                    <p class="badge badge-success">Data Nascimento</p>
                                    <p style="font-size: 18px"><?php echo date('d/m/Y', strtotime($paciente['data_nascimento']));?></p>
                                    <hr>
                                    <p class="badge badge-success">Endereço</p>
                                    <p style="font-size: 18px"><?php echo ucwords($paciente['rua']).', ';?>
                                    <?php echo $paciente['numero'].', ';?>
                                    <?php echo ucwords($paciente['bairro']).', ';?>
                                    <?php echo $paciente['cep'].', ';?>
                                    <?php echo ucwords($paciente['cidade']).' -';?>
                                    <?php echo $paciente['estado'];?></p>
                                    <hr>
                                    <p class="badge badge-success">Telefone</p>
                                    <p style="font-size: 18px"><?php echo $paciente['telefone'];?></p>
                                    <hr>
                                    <p class="badge badge-success">Status</p>
                                    <?php if($paciente['vida']==1):?>
                                    <p style="font-size: 18px">Vivo</p>
                                    <?php else:?>
                                    <br>
                                    <p class="badge badge-danger" style="font-size: 18px">Óbito em <?php echo date('d/m/Y', strtotime($dataObito['data_saida']));?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="modal-footer" style="background-color: #efefef">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM Modal Detalhes de Paciente -->
            <?php endforeach; ?>
            </tbody>
        </table>
        <ul class="pagination">
            <?php for($i=0;$i<$paginas;$i++): ?>
                <li class="page-item">
                    <a class="page-link" href="gerenciar-pacientes.php?<?php
                    $get = $_GET;//Aqui passa tudo que há no $_GET para a variável get.
                    $get['p'] = $i+1;
                    echo http_build_query($get);//Transforma todos os itens que há em $_GET em url.
                    ?>" ><?php echo $i+1; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</div>
<script type="text/javascript" src="modal-excluir-paciente.js"></script>
