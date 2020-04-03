<?php
    session_start();

    require_once __DIR__ . "/vendor/autoload.php";

    use Classes\Hospitais\UnidadeHospitalar;
    use Classes\Hospitais\UnidadeHospitalarDao;
    use Classes\HistoricoPaciente\HistoricoPaciente;
    use Classes\HistoricoPaciente\HistoricoPacienteDao;

    include_once 'pages/header.php';
    include_once 'pages/navbar.php';

    /*require_once 'Classes/Hospitais/UnidadeHospitalar.php';
    require_once 'Classes/Hospitais/UnidadeHospitalarDao.php';
    require_once 'Classes/HistoricoPaciente/HistoricoPacientes.php';
    require_once 'Classes/HistoricoPaciente/HistoricoPacientesDao.php';*/

    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    $historico = new HistoricoPaciente();
    $histDao = new HistoricoPacienteDao();

    $hospitais = $hd->getMaiorCasosCorona();
    $counCasos = $hd->countHospitaisHistorico($hospitais['id_hospital']);

    $countObitos = $hd->countObitos();

    $countDiagnosticoPositivo = $histDao->getDiagnosticosPositivos();

    //Comunicando com API do Corona Vírus:

    $urlBrasil2 = 'https://services1.arcgis.com/0MSEUqKaxRlEPj5g/arcgis/rest/services/Coronavirus_2019_nCoV_Cases/FeatureServer/1/query?where=OBJECTID=103&outFields=OBJECTID,Country_Region,Last_Update,Confirmed,Recovered,Deaths&outSR=4326&f=json';

    $ch = curl_init($urlBrasil2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $casosBr2 = json_decode(curl_exec($ch));

    //Lendo arquivo CSV
    //$file = 'csv/cases-brazil-total.csv';
    //Link para ler o csv direto do navegador.
    $linkCsv = 'https://labs.wesleycota.com/sarscov2/br/github/cases-brazil-total.csv';
    $csv = file($linkCsv);
    $estado = '';
    $casosPe = '';
    $mortesPe = '';
    $fonteEstados = 'https://labs.wesleycota.com/sarscov2/br/#fontes';
    $fonteBrasil = 'https://coronavirus-disasterresponse.hub.arcgis.com/datasets/bbb2e4f589ba40d692fab712ae37b9ac_1/geoservice?geometry=101.682%2C-38.069%2C-84.294%2C63.033';
    foreach ($csv as $row => $line){
        $row++;
        $column = str_getcsv($line, ',');
        //Pegando apenas a linha referente a Pernambuco, e as colunas que nos interessam no momento.
        if($row == 11){
            $estado = $column[1];
            $casosPe = $column[2];
            $mortesPe = $column[5];
        }
    }

?>
<body>
    <div class="container-fluid">
        <div class="row" style="margin-top: 15px">
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
                        <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 20%">Total de casos</th>
                            <th style="width: 20%">Total de mortes</th>
                            <th style="width: 50%">Fonte</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        <tr>
                            <td style="font-size: 16px"><?php echo $casosPe;?></td>
                            <td style="font-size: 16px"><?php echo $mortesPe;?></td>
                            <td style="font-size: 16px"><a style="text-decoration: none" href="<?php echo $fonteEstados; ?>" target="_blank">casos por estado .CSV</a></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="jumbotron-fluid text-center" style="background-color: #33b5e5; border-radius: 5px; color: whitesmoke"><h5>Informações do Brasil</h5></div>
                    <table class="table table-bordered table-responsive-sm table-sm bg-light">
                        <thead class="thead-light text-center">
                        <tr>
                            <th>Total de casos</th>
                            <th>Curados</th>
                            <th>Total de mortes</th>
                            <th>Fonte</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        <tr>
                            <td style="font-size: 16px"><?php echo $casosBr2->features[0]->attributes->Confirmed; ?></td>
                            <td style="font-size: 16px"><?php echo $casosBr2->features[0]->attributes->Recovered; ?></td>
                            <td style="font-size: 16px"><?php echo $casosBr2->features[0]->attributes->Deaths; ?></td>
                            <td><a style="text-decoration: none" href="<?php echo $fonteBrasil; ?>" target="_blank">coronavirusnobrasil.org</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

