<?php
    session_start();
if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require_once __DIR__ . '/vendor/autoload.php';

use Classes\HistoricoPaciente\HistoricoPaciente;
use Classes\HistoricoPaciente\HistoricoPacienteDao;

if(empty($_GET['id'])){
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}

/*require 'Classes/HistoricoPaciente/HistoricoPaciente.php';
require 'Classes/HistoricoPaciente/HistoricoPacienteDao.php';*/

$historico = new HistoricoPaciente();
$hd = new HistoricoPacienteDao();

$historico->setId(addslashes($_GET['id']));

if($hd->excluirHistorico($historico->getId())==true){
    $_SESSION['msg'] = "Histórico de paciente excluído com sucesso.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}else{
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}

?>
