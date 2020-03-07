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
require 'classes/historicoPaciente/historicoPacientes.class.php';
require 'classes/historicoPaciente/historicoPacientesDao.class.php';
require 'classes/hospitais/unidadeHospitalar.class.php';
require 'classes/hospitais/unidadeHospitalarDao.class.php';
require 'classes/pacientes/paciente.class.php';
require 'classes/pacientes/pacienteDao.class.php';

if(!empty($_GET['id']) && !empty($_GET['paciente'])) {
    $historico = new HistoricoPaciente();
    $hd = new HistoricoPacienteDao();
    $historico->setId(addslashes($_GET['id']));
    $selectedPaciente = new Paciente();
    $pd = new PacienteDao();
    $selectedPaciente->setNome(addslashes($_GET['paciente']));
}else{
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}

$info = $hd->getHistorico($historico->getId());

$diagnosticos = new Diagnostico();
$dd = new DiagnosticoDao();
$diagnosticos = $dd->getDiagnosticos();

$pacientes = new Paciente();

$hospitais = new UnidadeHospitalar();
$ud = new UnidadeHospitalarDao();

if(isset($_SESSION['id_usuario'])){
    $idUsuario = addslashes($_SESSION['id_usuario']);
    $hospitais = $ud->getHospitalUserLogado($idUsuario);
    $pacientes = $pd->getPacienteForUserLogado($idUsuario);
}elseif(isset($_SESSION['id_adm'])){
    $hospitais = $ud->getAllHospitais();
    $pacientes = $pd->getPacientes();
}

if(isset($_POST['hospital']) && !empty($_POST['hospital']) && isset($_POST['paciente']) && !empty($_POST['paciente'])
   && isset($_POST['saida']) && !empty($_POST['saida']) && isset($_POST['motivo']) && !empty($_POST['motivo'])){

    $historico->setMotivoAlta(addslashes($_POST['motivo']));
    $historico->setDataSaida(addslashes($_POST['saida']));

    if($hd->alterarHistoricoDataSaida($historico->getId(), $historico->getMotivoAlta(), $historico->getDataSaida())==true){
        $_SESSION['msg'] = "Data de saída atualizada com sucesso. Informação enviada para a tabela de altas médicas.";
        header('Location: gerenciar-historico-pacientes.php');
    }else{
        $_SESSION['msg'] = "Erro ao tentar lançar a data de saída.";
        header('Location: gerenciar-historico-pacientes.php');
    }

}

?>
<head>
    <meta charset="UTF-8">
    <title>Atualização de Histórico de Paciente</title>
</head>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Registrar Saída do Paciente</span></h2>
    </div>
    <div class="jumbotron text-center" style="margin-bottom: 5px">
        <h3 style="color: whitesmoke">Paciente: <?php echo $selectedPaciente->getNome();?></h3>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg = "Data de saída atualizada com sucesso. Informação enviada para a tabela de altas médicas."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }else{
            $msg = "Erro ao tentar lançar a data de saída.";
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
    <div class="col">
        <form class="form-group" method="POST">
            <label class="col-form-label-lg" hidden for="hospital">Unidade Hospitalar</label>
            <select class="form-control" name="hospital" hidden required>
                <option></option>
                <?php if(isset($_SESSION['id_adm'])): ?>
                    <?php foreach ($hospitais as $hospital): ?>
                        <option value="<?php echo $hospital['id'];?>" <?php echo ($info['id_hospital']==$hospital['id'])?'selected="selected"':''; ?> ><?php echo ucwords($hospital['nome']);?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="<?php echo $hospitais['id_hospital']; ?>" <?php echo ($info['id_hospital']==$hospitais['id_hospital'])?'selected="selected"':''; ?> ><?php echo ucwords($hospitais['hospital']); ?></option>
                <?php endif; ?>
            </select>

            <label class="col-form-label-lg" hidden for="paciente">Paciente</label>
            <select class="form-control" hidden name="paciente" required>
                <option></option>
                <!--Caso o usuário logado seja adm, todos os pacientes aparecerão para ele.-->
                <?php if(isset($_SESSION['id_adm'])): ?>
                    <?php foreach ($pacientes as $paciente): ?>
                        <option value="<?php echo $paciente['id']; ?>" <?php echo ($info['id_paciente']==$paciente['id'])?'selected="selected"':''; ?> ><?php echo ucwords($paciente['nome']); ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!--Caso o usuário logado não seja adm, apenas os pacientes do seu hospital aparecerão para ele.-->
                    <?php foreach ($pacientes as $paciente): ?>
                        <option value="<?php echo $paciente['id']; ?>" <?php echo ($info['id_paciente']==$paciente['id'])?'selected="selected"':''; ?> ><?php echo ucwords($paciente['nome']);?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <div>
                <label class="col-form-label-lg" hidden for="diagnostico">Diagnóstico</label>
                <select class="form-control" hidden name="diagnostico" required>
                    <option></option>
                    <?php foreach ($diagnosticos as $diagnostico): ?>
                        <option value="<?php echo $diagnostico['id']; ?>" <?php echo ($info['id_diagnostico']==$diagnostico['id'])?'selected="selected"':''; ?>><?php echo ucwords($diagnostico['status']);?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="col-form-label-lg" for="motivo">Motivo da saída</label>
                <select class="form-control" name="motivo" id="motivo" required>
                    <option></option>
                    <option value="1" <?php echo ($info['motivoalta'] == 1)?'selected = "selected"':'';?> >Alta Médica</option>
                    <option value="2" <?php echo ($info['motivoalta'] == 2)?'selected = "selected"':'';?> >Óbito</option>
                </select>
            </div>

            <div>
                <label class="col-form-label-lg" for="saida">Data de Saída</label>
                <input class="form-control" value="<?php echo $info['data_saida']; ?>" type="date" name="saida" id="dataSaida" required>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Atualizar Histórico</button>
            </div>
        </form>
    </div>
</section>
