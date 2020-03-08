<?php

session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

    require 'pages/header.php';
    require 'classes/diagnostico/diagnostico.class.php';
    require 'classes/diagnostico/diagnosticoDao.class.php';
    require 'classes/hospitais/unidadeHospitalar.class.php';
    require 'classes/hospitais/unidadeHospitalarDao.class.php';
    require 'classes/pacientes/pacienteDao.class.php';
    require 'classes/pacientes/paciente.class.php';

    $diagnosticos = new Diagnostico();
    $dd = new DiagnosticoDao();
    $diagnosticos = $dd->getDiagnosticos();
    $pacientes = new Paciente();
    $pd = new PacienteDao();
    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    if(isset($_SESSION['id_usuario'])){
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $hospitais = $hd->getHospitalUserLogado($idUsuario);
        $pacientes = $pd->getAllPacienteForUserLogado($idUsuario);
    }elseif(isset($_SESSION['id_adm'])){
        $hospitais = $hd->getAllHospitais();
        $pacientes = $pd->getAllPacientes();
    }

    if(isset($_GET['id']) && !empty($_GET['id'])){
        require 'classes/historicoPaciente/historicoPacientes.class.php';
        require 'classes/historicoPaciente/historicoPacientesDao.class.php';
        $historico = new HistoricoPaciente();
        $histDao = new HistoricoPacienteDao();
        $historico->setId(addslashes($_GET['id']));
        $info = $histDao->getHistorico($historico->getId());
    }

    if(isset($_POST['hospital']) && !empty($_POST['hospital']) && isset($_POST['paciente']) && !empty($_POST['paciente'])
        && isset($_POST['diagnostico']) && !empty($_POST['diagnostico']) && isset($_POST['entrada']) && !empty($_POST['entrada'])){
        $historico->setIdHospital(addslashes($_POST['hospital']));
        $historico->setIdPaciente(addslashes($_POST['paciente']));
        $historico->setIdDiagnostico(addslashes($_POST['diagnostico']));
        $historico->setDataEntrada(addslashes($_POST['entrada']));

        if($histDao->alterarHistorico($historico->getId(), $historico->getIdHospital(), $historico->getIdPaciente(), $historico->getIdDiagnostico(), $historico->getDataEntrada())==true){
            $_SESSION['msg'] = "Histórico alterado com sucesso.";
            header("Location: alterar-historico.php");
            exit;
        }else{
            $_SESSION['msg'] = "Erro ao tentar alterar Histórico.";
            header("Location: alterar-historico.php");
            exit;
        }
    }

?>

<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Alterar Histórico de Pacientes</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg = "Histórico alterado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg = "Erro ao tentar alterar Histórico."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
    <div class="col">
        <form class="form-group" method="POST">
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="hospital">Unidade Hospitalar</label>
                    <select class="form-control" name="hospital" id="hospitalhistorico" required>
                        <option></option>
                        <?php if(isset($_SESSION['id_adm'])): ?>
                            <?php foreach ($hospitais as $hospital): ?>
                                <option value="<?php echo $hospital['id'];?>" <?php echo ($info['id_hospital']==$hospital['id'])?'selected="selected"':'';?> ><?php echo ucwords($hospital['nome']);?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="<?php echo $hospitais['id_hospital']; ?>" <?php echo ($info['id_hospital']==$hospitais['id_hospital'])?'selected="selected"':'';?>><?php echo ucwords($hospitais['hospital']); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="paciente">Paciente</label>
                    <select class="form-control" name="paciente" id="pacientehistorico" required>
                        <option></option>
                        <!--Caso o usuário logado seja adm, todos os pacientes aparecerão para ele.-->
                        <?php if(isset($_SESSION['id_adm'])): ?>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>" <?php echo ($info['id_paciente']==$paciente['id'])?'selected="selected"':'';?> ><?php echo ucwords($paciente['nome']); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach ($pacientes as $paciente): ?>
                                <!--Caso o usuário logado não seja adm, apenas os pacientes do seu hospital aparecerão para ele.-->
                                <option value="<?php echo $paciente['id']; ?>" <?php echo ($info['id_paciente']==$paciente['id'])?'selected="selected"':'';?> ><?php echo ucwords($paciente['nome']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="diagnostico">Diagnóstico</label>
                    <select class="form-control" name="diagnostico" required>
                        <option></option>
                        <?php foreach ($diagnosticos as $diagnostico): ?>
                            <option value="<?php echo $diagnostico['id']; ?>" <?php echo ($info['id_diagnostico']==$diagnostico['id'])?'selected="selected"':''?> ><?php echo ucwords($diagnostico['status']);?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="entrada">Data de Entrada</label>
                    <input class="form-control" value="<?php echo $info['data_entrada'];?>" type="date" name="entrada" id="dataEntrada" required>
                </div>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Alterar</button>
            </div>
        </form>
    </div>
</section>