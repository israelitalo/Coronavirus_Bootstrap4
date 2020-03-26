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
require 'Classes/HistoricoPaciente/HistoricoPaciente.php';
require 'Classes/HistoricoPaciente/HistoricoPacienteDao.php';
require 'Classes/Hospitais/UnidadeHospitalar.php';
require 'Classes/Hospitais/UnidadeHospitalarDao.php';
require 'Classes/Pacientes/Paciente.php';
require 'Classes/Pacientes/PacienteDao.php';*/

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

if(isset($_POST['saida']) && !empty($_POST['saida']) && isset($_POST['motivo']) && !empty($_POST['motivo'])){

    $historico->setMotivoAlta(addslashes($_POST['motivo']));
    $historico->setDataSaida(addslashes($_POST['saida']));

    //Verificando se a data de entrada é menor que a data de saída.
    $dataEntrada = strtotime($info['data_entrada']);
    $dataSaida = strtotime($historico->getDataSaida());

    if($dataSaida < $dataEntrada){
        $_SESSION['msg'] = "A data de saída deve ser superior a data de entrada.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
        <?php
        //fim da verificação.
    }else{
        if($hd->alterarHistoricoDataSaida($historico->getId(), $historico->getMotivoAlta(), $historico->getDataSaida(), $info['id_paciente'])==true){
            $_SESSION['msg'] = "Data de saída atualizada com sucesso.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
            <?php
        }else{
            $_SESSION['msg'] = "Erro ao tentar lançar a data de saída.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
            <?php
        }
    }

}

?>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Registrar Saída</span></h2>
    </div>
    <div class="jumbotron text-center" style="margin-bottom: 5px">
        <h3 style="color: whitesmoke">Paciente: <?php echo $selectedPaciente->getNome();?></h3>
    </div>
    <div class="col">
        <form class="form-group" method="POST">
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
                <button class="btn btn-primary" type="submit">Registrar saída</button>
            </div>
        </form>
    </div>
</section>
