<?php
    session_start();

    //require __DIR__ . "/vendor/autoload.php";

    if(empty($_SESSION['id_usuario']) && empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    include_once 'pages/header.php';
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

    $countObitos = $hd->countObitos();

    $countDiagnosticoPositivo = $histDao->getDiagnosticosPositivos();

    //Comunicando com API do Corona Vírus:
    $url = 'https://api.coronaanalytic.com/brazil/26';
    $urlBrasil = 'https://api.coronaanalytic.com/world/BR';
    /*$casosPe = json_decode(file_get_contents($url));
    $casosBr = json_decode(file_get_contents($urlBrasil));*/

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $casosPe = json_decode(curl_exec($ch));

    $ch = curl_init($urlBrasil);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $casosBr = json_decode(curl_exec($ch));

?>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5" id="div-carousel-index" style="margin-top: 10px; transition: 1s;">
                <div class="align-items-center" style="margin-left: 10px; transition: 1s;">
                    <!-- Carousel -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                        </ol>
                        <div class="carousel-inner" style="border-radius: 5px">
                            <div class="carousel-item active">
                                <img style="border-radius: 5px" id="imgcorona0" height="320" class="d-block w-100" src="assets/images/carousel/imagem_0.jpg" alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img style="border-radius: 5px" id="imgcorona1" height="320" class="d-block w-100" src="assets/images/carousel/imagem_1.png" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img style="border-radius: 5px" id="imgcorona2" height="320" class="d-block w-100" src="assets/images/carousel/imagem_2.jpg" alt="Third slide">
                            </div>
                            <div class="carousel-item">
                                <img style="border-radius: 5px" id="imgcorona3" height="320" class="d-block w-100" src="assets/images/carousel/imagem_3.png" alt="Four slide">
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
                <div class="jumbotron-fluid text-center" style="margin-left: 2%; margin-bottom: 20px; margin-top: 20px; background-color: #6c757d; border-radius: 5px; color: whitesmoke"><h5>Links importantes</h5></div>
                <div class="row" id="links-importantes" style="margin-top: 15px">
                    <div class="col" id="col-link-1" style="margin-bottom: 20px">
                        <a id="link-1" href="https://coronavirus.jhu.edu/map.html" target="_blank"><img id="img-link-1" src="assets/images/icones/john-hopkins-logo.jpg" width="120" height="120"></a>
                    </div>
                    <div class="col" id="col-link-2" style="margin-bottom: 20px">
                        <a id="link-2" href="https://arte.estadao.com.br/ciencia/novo-coronavirus/monitor-pandemia/" target="_blank"><img id="img-link-2" src="assets/images/icones/estadao.png" width="120" height="120"></a>
                    </div>
                    <div class="col" id="col-link-3" style="margin-bottom: 20px">
                        <a id="link-3" href="https://saude.gov.br/" target="_blank"><img id="img-link-3" src="assets/images/icones/mds.png" width="120" height="120"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-7" id="div-top-casos" style="margin-top: 10px; transition: 1s">
                <div class="col" id="div-col-cima">
                    <div class="jumbotron-fluid text-center" style="background-color: #33b5e5; border-radius: 5px; color: whitesmoke"><h4>Informações CvSoftware</h4></div>
                    <div class="jumbotron-fluid text-center" style="background-color: #6c757d; border-radius: 5px; color: whitesmoke"><h5>Maior n° de atendimento</h5></div>
                    <table class="table table-sm bg-light">
                        <thead></thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 16px"><?php echo ucwords($hospitais['hospital']);?></td>
                            <td style="font-size: 16px"><?php echo $counCasos['total'];?> paciente(s)</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="jumbotron-fluid text-center" style="background-color: #6c757d; border-radius: 5px; color: whitesmoke"><h5>Casos confirmados</h5></div>
                    <table class="table table-sm bg-light">
                        <thead></thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 16px"><?php echo $countDiagnosticoPositivo['total'];?> caso(s)</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="jumbotron-fluid text-center" style="background-color: #6c757d; border-radius: 5px; color: whitesmoke"><h5>Óbitos confirmados</h5></div>
                    <table class="table table-sm bg-light">
                        <thead></thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 16px"><?php echo $countObitos['total'];?> óbito(s)</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col" id="div-col-baixo" style="transition: 1s; border-radius: 5px">
                    <div class="jumbotron-fluid text-center" style="background-color: #33b5e5; border-radius: 5px; color: whitesmoke"><h5>Informações de Pernambuco</h5></div>
                    <table class="table table-bordered table-responsive-sm table-sm bg-light">
                        <thead class="thead-light">
                        <tr>
                            <th>Confirmados</th>
                            <th>Suspeitos</th>
                            <th>Negativos</th>
                            <th>Óbitos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 16px"><?php echo $casosPe->cases; ?></td>
                            <td style="font-size: 16px"><?php echo $casosPe->suspects; ?></td>
                            <td style="font-size: 16px"><?php echo $casosPe->refuses; ?></td>
                            <td style="font-size: 16px"><?php echo $casosPe->deaths; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="jumbotron-fluid text-center" style="background-color: #33b5e5; border-radius: 5px; color: whitesmoke"><h5>Informações do Brasil</h5></div>
                    <table class="table table-bordered table-responsive-sm table-sm bg-light">
                        <caption style="font-size: 10px"><?php echo $casosBr->comments; ?> <span class="badge-pill badge-primary"><a style="text-decoration: none; color: whitesmoke; font-size: 11px" href="https://github.com/rodrilima/corona-analytic-api" target="_blank">api</a></span></caption>
                        <thead class="thead-light">
                        <tr>
                            <th>Confirmados</th>
                            <th>Casos Recentes</th>
                            <th>Óbitos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 16px"><?php echo $casosBr->cases; ?></td>
                            <td style="font-size: 16px"><?php echo $casosBr->casesNew; ?></td>
                            <td style="font-size: 16px"><?php echo $casosBr->deaths; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

