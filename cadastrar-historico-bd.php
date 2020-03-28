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

if(isset($_POST['hospital']) && !empty($_POST['hospital']) && isset($_POST['paciente']) && !empty($_POST['paciente'])
    && isset($_POST['diagnostico']) && !empty($_POST['diagnostico']) && isset($_POST['entrada']) && !empty($_POST['entrada'])){

    /*require 'Classes/HistoricoPaciente/HistoricoPaciente.php';
    require 'Classes/HistoricoPaciente/HistoricoPacienteDao.php';*/

    $historico = new HistoricoPaciente();
    $hd = new HistoricoPacienteDao();

    $historico->setIdHospital(addslashes($_POST['hospital']));
    $historico->setIdPaciente(addslashes($_POST['paciente']));
    $historico->setIdDiagnostico(addslashes($_POST['diagnostico']));
    $historico->setDataEntrada(addslashes($_POST['entrada']));

    //Verificar aqui se existe um histórico com data de entrada anterior à data cadastrada, sem data de saída.
    $countHistoricoEmAberto = $hd->countHistoricoEmAberto($historico->getIdPaciente());

    if($countHistoricoEmAberto['total'] > 0){
        $_SESSION['msg'] = "O paciente já possui um histórico sem data de saída informada.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
        <?php
    }else{
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
}
else{
    $_SESSION['msg'] = "Preencha todos os campos e tente realizar o registro novamente.";
    ?>
    <script type="text/javascript">window.location.href="gerenciar-historico-pacientes.php";</script>
    <?php
}

?>
