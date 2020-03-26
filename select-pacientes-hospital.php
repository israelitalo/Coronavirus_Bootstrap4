<?php

    session_start();

    if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
        ?>
        <script type="text/javascript">window.location.href="login.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    use Classes\Pacientes\Paciente;
    use Classes\Pacientes\PacienteDao;
    /*require_once 'Classes/Pacientes/Paciente.php';
    require_once 'Classes/Pacientes/PacienteDao.php';*/

    if(isset($_POST['hospital'])){

        $idHospital = addslashes($_POST['hospital']);
        $pacientes = new Paciente();
        $pd = new PacienteDao();

        $pacientes = $pd->getPacientePorHospital($idHospital);

        foreach ($pacientes as $paciente):?>
        <option value="<?php echo $paciente['id'];?>"><?php echo $paciente['nome']?></option>
        <?php endforeach;

    }

?>
