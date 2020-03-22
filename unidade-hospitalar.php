<?php

    session_start();

    if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])){
        include_once 'pages/header.php';
        require_once 'classes/hospitais/UnidadeHospitalar.php';
        require_once 'classes/hospitais/UnidadeHospitalarDao.php';
        require_once 'classes/historicoPaciente/HistoricoPacientes.php';
        require_once 'classes/historicoPaciente/HistoricoPacientesDao.php';
        require_once 'classes/pacientes/Paciente.php';
        require_once 'classes/pacientes/PacienteDao.php';

        $hd = new UnidadeHospitalarDao();
        $idHospital = $hd->getIdHospital($_SESSION['id_usuario']);
        $hospital = $hd->getHospital($idHospital);
        $atendimentos = $hd->countHospitaisHistorico($idHospital);

        $histDao = new HistoricoPacienteDao();
        $confirmados = $histDao->countHistoricoPositivos($idHospital);

        $pd = new PacienteDao();
        $obitos = $pd->getObitosHospital($idHospital);

    }
?>

<body>
    <div class="container">
        <div class="jumbotron bg-secondary text-center" style="margin-top: 15px; color: whitesmoke">
            <h3>Informações do <?php echo ucwords($hospital['nome']); ?></h3>
        </div>
        <table class="table table-light table-bordered table-sm" style="border-radius: 5px">
            <thead class="thead-dark">
            <tr class="text-center" style="font-size: 20px">
                <th>Atendimentos</th>
                <th>Confirmados</th>
                <th>Óbitos</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-center" style="font-size: 20px">
                <td class="text-success font-weight-bold"><?php echo $atendimentos['total'];?></td>
                <td class="text-secondary font-weight-bold"><?php echo $confirmados['total'];?></td>
                <td class="text-danger font-weight-bold"><?php echo $obitos['total'];?></td>
            </tr>
            </tbody>
        </table>
    </div>
</body>
