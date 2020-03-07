<?php
session_start();
if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    require 'sair.php';
}

require 'pages/header.php';
require 'classes/historicoPaciente/historicoPacientes.class.php';
require 'classes/historicoPaciente/historicoPacientesDao.class.php';

$historicos = new HistoricoPaciente();
$historicoDao = new HistoricoPacienteDao();

if(isset($_SESSION['id_adm'])){
    $historicos = $historicoDao->getAllHistoricos();
}elseif(isset($_SESSION['id_usuario'])){
    $idUsuario = addslashes($_SESSION['id_usuario']);
    $historicos = $historicoDao->getHistoricoPorUsuario($idUsuario);
}

if(isset($_GET['busca'])){
    $busca = addslashes($_GET['busca']);
    if(isset($_SESSION['id_adm'])){
        $historicos = $historicoDao->getHistoricoLike($busca);
    }elseif(isset($_SESSION['id_usuario'])){
        $historicos = $historicoDao->getHistoricoLikeForUserLogado($busca, $idUsuario);
    }
}

?>

<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Histórico de Pacientes</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg == "Data da alta alterada com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Data da alta inserida com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Data do obito alterada com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Data do obito inserida com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-historico.php" role="button">Adicionar</a>
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
            <caption>Histórico de Pacientes</caption>
            <thead class="thead-dark">
            <tr>
                <th>Hospital</th>
                <th>Paciente</th>
                <th>Diagnóstico</th>
                <th>Data de entrada</th>
                <th>Data de saída</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($historicos as $historico): ?>
                <tr>
                    <td style="width: 20%; padding-top: 20px"><?php echo ucwords($historico['hospital']); ?></td>
                    <td style="width: 15%; padding-top: 20px">
                        <a data-toggle="modal" data-target="#exampleModalLong<?php echo $historico['id'] ;?>" href="#"
                           class="badge badge-primary">
                            <span style="font-size: 16px">
                                <?php
                                //Para exibir apenas o primeiro e último nome do Paciente.
                                $nome = $historico['paciente'];
                                $temp = explode(" ", $nome);
                                $novoNome = $temp[0]." ".$temp[count($temp)-1];
                                echo ucwords($novoNome);
                                ?>
                            </span>
                        </a>
                    </td>
                    <td style="width: 15%; padding-top: 20px"><?php echo ucwords($historico['diagnostico']); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo date('d/m/Y', strtotime($historico['data_entrada'])); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php
                        /*A condição abaixo serve para os casos que a data de saída ainda não foi lançada para o histórico do paciente, pois, caso
                        seja usado o date('d/m/Y', strtotime()) em um data como null, será mostrada ao usuário uma data de 1970.*/
                        if($historico['data_saida'] == null){
                            $historico['data_saida'] = 'aguardando saída';
                            echo $historico['data_saida'];
                        }else{
                            echo date('d/m/Y', strtotime($historico['data_saida']));
                        }?>
                    </td>
                    <td class="text-center" style="width: 15%>">
                        <a class="btn btn-outline-warning" href="alterar-historico.php?id=<?php echo $historico['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" data-confirm="Deseja realmente excluir o historico selecionado?" href="excluir-historico.php?id=<?php echo $historico['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" href="alterar-historico-data-saida.php?id=<?php echo $historico['id']; ?>&paciente=<?php echo $historico['paciente']; ?>"><img id="icone-lista" src="assets/images/icones/documento.png"></a>
                    </td>
                </tr>
                <!-- Modal Histórico de Paciente -->
                <div class="modal fade" id="exampleModalLong<?php echo $historico['id'] ;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #c8cbcf">
                                <h5 class="modal-title" style="font-size: 20px" id="exampleModalLongTitle"><?php echo $historico['paciente'];?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="background-color: #efefef">
                                <p style="font-size: 20px">Motivo da saída: </p>
                                <?php
                                    if($historico['motivoalta']==1):
                                        ?><p class="badge badge-success h4" style="font-size: 18px">Alta Médica</p><?php
                                    elseif($historico['motivoalta']==2):
                                        ?><p class="badge badge-danger h4" style="font-size: 18px">Óbito</p><?php
                                    else:
                                        ?><p class="badge badge-warning h4" style="font-size: 18px">Paciente encontra-se na unidade</p><?php
                                    endif;
                                ?>
                            </div>
                            <div class="modal-footer" style="background-color: #efefef">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM Modal Histórico de Paciente -->
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="modal-excluir-historico.js"></script>
