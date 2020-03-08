<?php
    session_start();
if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

if(empty($_GET['id'])){
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}

require 'classes/historicoPaciente/historicoPacientes.class.php';
require 'classes/historicoPaciente/historicoPacientesDao.class.php';

$historico = new HistoricoPaciente();
$hd = new HistoricoPacienteDao();

$historico->setId(addslashes($_GET['id']));

if($hd->excluirHistorico($historico->getId())==true){
    $_SESSION['msg'] = "Histórico de paciente excluído com sucesso.";
    header("Location: gerenciar-historico-pacientes.php");
    exit;
}else{
    $_SESSION['msg'] = "Erro ao tentar excluir histórico de paciente.";
    header("Location: gerenciar-historico-pacientes.php");
    exit;
}

?>
