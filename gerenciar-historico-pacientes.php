<?php
    session_start();
    if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    include_once 'pages/header.php';
    include_once 'pages/navbar.php';

    /*require 'Classes/HistoricoPaciente/HistoricoPaciente.php';
    require 'Classes/HistoricoPaciente/HistoricoPacienteDao.php';*/

    use Classes\HistoricoPaciente\HistoricoPaciente;
    use Classes\HistoricoPaciente\HistoricoPacienteDao;

    $historicos = new HistoricoPaciente();
    $historicoDao = new HistoricoPacienteDao();

    $qtPaginas = 6;
    $pg = 1;

    if(isset($_GET['p']) && !empty($_GET['p'])){
        $pg = addslashes($_GET['p']);
    }

    $p = ($pg - 1) * $qtPaginas;

    if(isset($_SESSION['id_adm'])){
        $countHistorico = $historicoDao->countHistoricoAll();
        $paginas = $countHistorico['total'] / $qtPaginas;
        $historicos = $historicoDao->getAllHistoricosPaginacao($p, $qtPaginas);
        //$historicos = $historicoDao->getAllHistoricos();
    }elseif(isset($_SESSION['id_usuario'])){
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $countHistoricoUsuario = $historicoDao->countHistoricosUsuario($idUsuario);
        $paginas = $countHistoricoUsuario['total'] / $qtPaginas;
        $historicos = $historicoDao->getAllHistoricoForUserLogado($idUsuario, $p, $qtPaginas);
        //$historicos = $historicoDao->getHistoricoPorUsuario($idUsuario);
    }

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        if(isset($_SESSION['id_adm'])){

            $countHistoricoComLike = $historicoDao->countHistoricoComLike($busca);
            $paginas = $countHistoricoComLike['total'] / $qtPaginas;
            $historicos = $historicoDao->getHistoricoLikePaginacao($busca, $p, $qtPaginas);
            //$historicos = $historicoDao->getHistoricoLike($busca);
        }elseif(isset($_SESSION['id_usuario'])){

            $countHistoricoUsuarioLike = $historicoDao->countHistoricoUsuarioLike($idUsuario, $busca);
            $paginas = $countHistoricoUsuarioLike['total'] / $qtPaginas;
            $historicos = $historicoDao->getHistoricoLikeForUserLogado($busca, $idUsuario, $p, $qtPaginas);
            //$historicos = $historicoDao->getHistoricoLikeForUserLogado($busca, $idUsuario);
        }
    }

?>
<body>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Históricos</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg == "Data de saída atualizada com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Erro ao tentar lançar a data de saída."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Histórico de paciente excluído com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif ($msg == "Erro ao tentar excluir histórico de paciente."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Histórico alterado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Erro ao tentar alterar Histórico."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Histórico de paciente cadastrado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Verifique se o paciente em questão veio a óbito."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Preencha todos os campos e tente realizar o registro novamente."){
            ?>
            <div class="alert alert-warning"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "A data de saída deve ser superior a data de entrada."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "O paciente já possui um histórico sem data de saída informada."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-6">
            <a class="btn btn-success" href="cadastrar-historico.php" role="button" style="margin-right: 10px">Adicionar</a>
            <a class="btn btn-outline-secondary" href="imprimirHistorico.php?busca=<?php echo (isset($_GET['busca']) && !empty($_GET['busca']))?$_GET['busca']:''; ?>" target="_blank" style="color: white; margin-right: 10px"><img src="assets/images/icones/impressora.png"></a>
            <button style="height: 25px; width: 25px; margin-top: 15px" type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Internado"></button>
            <button style="height: 25px; width: 25px; margin-top: 15px" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Alta médica"></button>
            <button style="height: 25px; width: 25px; margin-top: 15px" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Óbito"></button>
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
            <caption>Histórico de Pacientes</caption>
            <thead class="thead-dark">
            <tr>
                <th>Paciente</th>
                <th>Hospital</th>
                <th>Diagnóstico</th>
                <th>Data de entrada</th>
                <th>Data de saída</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($historicos as $historico): ?>
                <tr>
                    <td style="width: 15%; padding-top: 20px">
                        <a data-toggle="modal" data-target="#exampleModalLong<?php echo $historico['id'] ;?>" href="#"
                           <?php
                           if($historico['motivoalta'] == 1){
                           ?>class="badge badge-success"<?php
                           }
                           elseif($historico['motivoalta'] == 2){
                           ?>class="badge badge-danger"<?php
                           }
                           else{
                           ?>class="badge badge-primary"<?php
                        }
                        ?>
                        >
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
                    <td style="width: 20%; padding-top: 20px"><?php echo ucwords($historico['hospital']); ?></td>
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
                                <h5 class="modal-title" style="font-size: 20px" id="exampleModalLongTitle"><?php echo ucwords($historico['paciente']);?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="background-color: #efefef">
                                <p style="font-size: 20px">Motivo da saída: </p>
                                <?php
                                if($historico['motivoalta']==null):?>
                                    <p class="badge badge-warning h4" style="font-size: 18px">Paciente encontra-se na unidade</p>
                                <?php elseif($historico['motivoalta']==1):?>
                                    <p class="badge badge-success h4" style="font-size: 18px">Alta Médica</p>
                                <?php elseif($historico['motivoalta']==2):?>
                                    <p class="badge badge-danger h4" style="font-size: 18px">Óbito</p>
                                <?php endif;?>
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
        <ul class="pagination">
            <?php for($i=0;$i<$paginas;$i++): ?>
                <li class="page-item">
                    <a class="page-link" href="gerenciar-historico-pacientes.php?<?php
                    $get = $_GET;//Aqui passa tudo que há no $_GET para a variável get.
                    $get['p'] = $i+1;
                    echo http_build_query($get);//Transforma todos os itens que há em $_GET em url.
                    ?>" ><?php echo $i+1; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</div>
</body>
<script type="text/javascript" src="modal-excluir-historico.js"></script>
