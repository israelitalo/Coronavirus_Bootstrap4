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

if($pd->excluirPaciente($paciente->getId())){
    $_SESSION['msg'] = "Paciente excluído(a) com sucesso.";
    header("Location: gerenciar-pacientes.php");
    exit;
}else{
    $_SESSION['msg'] = "Erro ao tentar excluir o(a) paciente.";
    header("Location: gerenciar-pacientes.php");
    exit;
}

?>
