<?php
    session_start();

    require __DIR__ . "/vendor/autoload.php";

    if(empty($_SESSION['id_usuario']) && empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }
    require_once 'init.php';
    require_once 'pages/header.php';
    require_once 'classes/hospitais/UnidadeHospitalar.php';
    require_once 'classes/hospitais/UnidadeHospitalarDao.php';
    require_once 'classes/historicoPaciente/HistoricoPacientes.php';
    require_once 'classes/historicoPaciente/HistoricoPacientesDao.php';

    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    $historico = new HistoricoPaciente();
    $histDao = new HistoricoPacienteDao();

    $hospitais = $hd->getMaiorCasosCorona();
    $counCasos = $hd->countHospitaisHistorico($hospitais['id_hospital']);

    $countDiagnosticoPositivo = $histDao->getDiagnosticosPositivos();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
    <title>Estatística covid-19</title>
</head>
<body>
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-5" id="div-carousel-index" style="padding: 0; margin-top: 10px">
                <div class="align-items-center" style="margin-left: 10px">
                    <!-- Carousel -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img id="imgcorona0" height="589" class="d-block w-100" src="assets/images/carousel/imagem_0.jpg" alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img id="imgcorona1" height="589" class="d-block w-100" src="assets/images/carousel/imagem_1.png" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img id="imgcorona2" height="589" class="d-block w-100" src="assets/images/carousel/imagem_2.jpg" alt="Third slide">
                            </div>
                            <div class="carousel-item">
                                <img id="imgcorona3" height="589" class="d-block w-100" src="assets/images/carousel/imagem_3.png" alt="Four slide">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-7" style="padding: 0; margin-top: 10px">
                <div class="col" style="height: 289px; margin-bottom: 10px">
                    <div class="col">
                        <h3><span class="badge badge-secondary">Top nº de atendimentos</span></h3>
                    </div>
                    <div class="col">
                        <table class="table table-sm bg-light">
                            <thead></thead>
                            <tbody>
                            <tr>
                                <td><h5><?php echo ucwords($hospitais['hospital']);?></h5></td>
                                <td><h5><?php echo $counCasos['total'];?> paciente(s)</h5></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col">
                        <h3><span class="badge badge-secondary">Casos confirmados</span></h3>
                    </div>
                    <div class="col">
                        <table class="table table-sm bg-light">
                            <thead></thead>
                            <tbody>
                            <tr>
                                <td><h5><?php echo $countDiagnosticoPositivo['total'];?> caso(s)</h5></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col" style="height: 289px">

                </div>
            </div>

        </div>


    </div>
</body>

