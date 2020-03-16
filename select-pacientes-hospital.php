<?php

    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="login.php";</script>
        <?php
    }

    require_once 'classes/pacientes/paciente.class.php';
    require_once 'classes/pacientes/pacienteDao.class.php';

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
