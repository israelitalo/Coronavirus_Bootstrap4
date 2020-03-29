<?php

session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

    include_once 'pages/header.php';
    include_once 'pages/navbar.php';

    require_once __DIR__ . '/vendor/autoload.php';

    use Classes\Diagnostico\Diagnostico;
    use Classes\Diagnostico\DiagnosticoDao;
    use Classes\HistoricoPaciente\HistoricoPaciente;
    use Classes\HistoricoPaciente\HistoricoPacienteDao;
    use Classes\Hospitais\UnidadeHospitalar;
    use Classes\Hospitais\UnidadeHospitalarDao;
    use Classes\Pacientes\Paciente;
    use Classes\Pacientes\PacienteDao;

    /*require 'Classes/Diagnostico/Diagnostico.php';
    require 'Classes/Diagnostico/DiagnosticoDao.php';
    require 'Classes/Hospitais/UnidadeHospitalar.php';
    require 'Classes/Hospitais/UnidadeHospitalarDao.php';
    require 'Classes/Pacientes/PacienteDao.php';
    require 'Classes/Pacientes/Paciente.php';*/

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
        $pacientes = $pd->getAllPacienteForUserLogadoSemPag($idUsuario);
    }elseif(isset($_SESSION['id_adm'])){
        $hospitais = $hd->getAllHospitais();
        $pacientes = $pd->getAllPacientes();
    }

    if(isset($_GET['id']) && !empty($_GET['id'])){
        require 'Classes/HistoricoPaciente/HistoricoPaciente.php';
        require 'Classes/HistoricoPaciente/HistoricoPacienteDao.php';
        $historico = new HistoricoPaciente();
        $histDao = new HistoricoPacienteDao();
        $historico->setId(addslashes($_GET['id']));
        $info = $histDao->getHistorico($historico->getId());

        $pacientesDeUmHospital = new Paciente();
        $pacientesDeUmHospital = $pd->getPacientePorHospital($info['id_hospital']);
    }

    if(isset($_POST['hospital']) && !empty($_POST['hospital']) && isset($_POST['paciente']) && !empty($_POST['paciente'])
        && isset($_POST['diagnostico']) && !empty($_POST['diagnostico']) && isset($_POST['entrada']) && !empty($_POST['entrada'])){
        $historico->setIdHospital(addslashes($_POST['hospital']));
        $historico->setIdPaciente(addslashes($_POST['paciente']));
        $historico->setIdDiagnostico(addslashes($_POST['diagnostico']));
        $historico->setDataEntrada(addslashes($_POST['entrada']));

        if($histDao->alterarHistorico($historico->getId(), $historico->getIdHospital(), $historico->getIdPaciente(), $historico->getIdDiagnostico(), $historico->getDataEntrada())==true){
            $_SESSION['msg'] = "Histórico alterado com sucesso.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
            <?php
        }else{
            $_SESSION['msg'] = "Erro ao tentar alterar Histórico.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
            <?php
        }
    }

?>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Alterar Histórico</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST">
            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="hospital">Hospital</label>
                    <select class="form-control" name="hospital" id="alterarhospitalhistorico" required>
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
                <div class="col-6">
                    <label class="col-form-label-lg" for="paciente">Paciente</label>
                    <select class="form-control" name="paciente" id="alterarpacientehistorico" required>
                        <?php foreach ($pacientesDeUmHospital as $pacienteHospital): ?>
                        <option value="<?php echo $pacienteHospital['id'];?>" <?php echo ($pacienteHospital['id']==$info['id_paciente'])?'selected="selected"':'';?>><?php echo ucwords($pacienteHospital['nome']);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <script type="text/javascript">
                $('#alterarhospitalhistorico').change(function () {
                    var idHospital = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "select-pacientes-hospital.php",
                        data:{
                            hospital: idHospital
                        },
                        success: function (retorno) {
                            var html = '';
                            $('#alterarpacientehistorico').html(html);
                            $('#alterarpacientehistorico').append(retorno);
                        }
                    });
                });
            </script>
            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="diagnostico">Diagnóstico</label>
                    <select class="form-control" name="diagnostico" required>
                        <option></option>
                        <?php foreach ($diagnosticos as $diagnostico): ?>
                            <option value="<?php echo $diagnostico['id']; ?>" <?php echo ($info['id_diagnostico']==$diagnostico['id'])?'selected="selected"':''?> ><?php echo ucwords($diagnostico['status']);?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
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
