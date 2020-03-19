<?php
session_start();
if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

if(isset($_POST['hospital']) && !empty($_POST['hospital']) && isset($_POST['paciente']) && !empty($_POST['paciente'])
    && isset($_POST['diagnostico']) && !empty($_POST['diagnostico']) && isset($_POST['entrada']) && !empty($_POST['entrada'])){

    require 'classes/historicoPaciente/HistoricoPacientes.php';
    require 'classes/historicoPaciente/HistoricoPacientesDao.php';

    $historico = new HistoricoPaciente();
    $hd = new HistoricoPacienteDao();

    $historico->setIdHospital(addslashes($_POST['hospital']));
    $historico->setIdPaciente(addslashes($_POST['paciente']));
    $historico->setIdDiagnostico(addslashes($_POST['diagnostico']));
    $historico->setDataEntrada(addslashes($_POST['entrada']));

    if($hd->addHistorico($historico->getIdHospital(), $historico->getIdPaciente(), $historico->getIdDiagnostico(), $historico->getDataEntrada())==true){
        $_SESSION['msg'] = "Histórico de paciente cadastrado com sucesso.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
        <?php
    }else{
        $_SESSION['msg'] = "Verifique se o paciente em questão veio a óbito.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
        <?php
    }
}
else{
    $_SESSION['msg'] = "Preencha todos os campos e tente realizar o registro novamente.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}

?>
