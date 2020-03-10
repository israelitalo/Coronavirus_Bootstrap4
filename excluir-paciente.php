<?php
session_start();
if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

if(empty($_GET['id'])){
    ?>
    <script type="text/javascript">window.location.href="gerenciar-pacientes.php";</script>
    <?php
}

require 'classes/pacientes/paciente.class.php';
require 'classes/pacientes/pacienteDao.class.php';

$paciente = new Paciente();
$pd = new PacienteDao();

$paciente->setId(addslashes($_GET['id']));

$countPaciente = $pd->countPacientesEmHistorico($paciente->getId());

if($countPaciente['total'] == 0){
    $pd->excluirPaciente($paciente->getId());
    $_SESSION['msg'] = "Paciente excluído(a) com sucesso.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-pacientes.php";</script>
    <?php
}else{
    $_SESSION['msg'] = "O paciente não pode ser excluído, pois está vinculádo a um histórico de paciente.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-pacientes.php";</script>
    <?php
}

?>
