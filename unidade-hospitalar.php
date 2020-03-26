<?php

    session_start();

    require_once __DIR__ . '/vendor/autoload.php';

    use Classes\Hospitais\UnidadeHospitalarDao;
    use Classes\HistoricoPaciente\HistoricoPacienteDao;
    use Classes\Pacientes\PacienteDao;

    include_once 'pages/header.php';
    include_once 'pages/navbar.php';

    /*require_once 'Classes/Hospitais/UnidadeHospitalar.php';
    require_once 'Classes/Hospitais/UnidadeHospitalarDao.php';
    require_once 'Classes/HistoricoPaciente/HistoricoPaciente.php';
    require_once 'Classes/HistoricoPaciente/HistoricoPacienteDao.php';
    require_once 'Classes/Pacientes/Paciente.php';
    require_once 'Classes/Pacientes/PacienteDao.php';*/

    if(isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])){

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
