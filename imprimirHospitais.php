<?php

    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    use Dompdf\Dompdf;
    use Classes\Hospitais\UnidadeHospitalar;
    use Classes\Hospitais\UnidadeHospitalarDao;
    /*require 'Classes/Hospitais/UnidadeHospitalar.php';
    require 'Classes/Hospitais/UnidadeHospitalarDao.php';*/

    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $hospitais = $hd->getHospitalLikeRel($busca);
    }else{
        $hospitais = $hd->getAllHospitais();
    }

     // DEFINE O FUSO HORARIO COMO O HORARIO DE Recife
    date_default_timezone_set('America/Recife');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    $data = date('d/m/Y H:i:s', time());

    $html = '
            <head>
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <title>Relatório de hospitais</title>
            <style>
                .pagenum:before {content: counter(page);}
                footer .pagenum:before {content: counter(page);}
            </style>
            </head>
        ';
    $html .= '<div class="text-right" style="margin-top:30px; font-size: 12px">
                    <p style="margin: 0">Data:'.$data.'</p>
                    <p style="margin: 0">Todos os hospitais</p>
                    <p style="margin: 0">filtro: '.$_GET['busca'].'</p>
                  </div>';
    $html .= '<div class="h3 card-header text-left">CvSoftware: Hospitais Cadastrados</div>';
    $html .= '<table class="table table-light table-borderless">';
    $html .= '<thead class="thead-dark" style="font-size: 14px">';
    $html .= '<tr>';
    $html .= '<th style="width: 35%">Hospital</th>';
    $html .= '<th style="width: 30%">CNPJ</th>';
    $html .= '<th style="width: 20%">Telefone</th>';
    $html .= '<th style="width: 15%">Cidade</th>';
    $html .= '<th style="width: 15%">Estado</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody style="font-size: 12px">';
    foreach ($hospitais as $hospital):
        $html .= '<tr>';
        $html .= '<td>'.ucwords($hospital['nome']).'</td>';
        $html .= '<td>'.ucwords($hospital['cnpj']).'</td>';
        $html .= '<td>'.$hospital['telefone'].'</td>';
        $html .= '<td>'.ucwords($hospital['cidade']).'</td>';
        $html .= '<td>'.ucwords($hospital['estado']).'</td>';
        $html .= '</tr>';
    endforeach;
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<div style="position: fixed; bottom: 0; width: 100%; background-color: #cccccc; padding-bottom: 3px">
                    <div class="pagenum-container" style="font-size: 11px; padding-left: 3px">
                        <span class="pagenum"></span> - Relatório emitido pela equipe CvSoftware
                    </div>
                  </div>';

    $domPdf = new Dompdf();

    $domPdf->loadHtml($html);

    $domPdf->setPaper("A4");

    $domPdf->render();

    $domPdf->stream("file.pdf", ["Attachment" => false]);

?>
