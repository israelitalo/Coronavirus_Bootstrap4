<?php

session_start();
if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Classes\Adm\Adm;
use Classes\Adm\AdmDao;

$adms = new Adm();
$admDao = new AdmDao();

if(isset($_GET['busca'])){
    $busca = addslashes($_GET['busca']);
    $adms = $admDao->getAdmLikeRel($busca);
}else{
    $adms = $admDao->getAllAdm();
}

// DEFINE O FUSO HORARIO COMO O HORARIO DE Recife
date_default_timezone_set('America/Recife');
// CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
$data = date('d/m/Y H:i:s', time());

$html = '
        <head>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <title>Relatório de Administradores</title>
        <style>
            .pagenum:before {content: counter(page);}
            footer .pagenum:before {content: counter(page);}
        </style>
        </head>
    ';
$html .= '<div class="text-right" style="margin-top:30px; font-size: 12px">
                        <p style="margin: 0">Data:'.$data.'</p>
                        <p style="margin: 0">Todos os administradores</p>
                        <p style="margin: 0">filtro: '.$_GET['busca'].'</p>
                      </div>';
$html .= '<div class="h3 card-header text-left">CvSoftware: Administradores Cadastrados</div>';
$html .= '<table class="table table-light table-borderless">';
$html .= '<thead class="thead-dark" style="font-size: 14px">';
$html .= '<tr>';
$html .= '<th style="width: 150px">Nome</th>';
$html .= '<th style="width: 60px">Login</th>';
$html .= '<th style="width: 50px">Telefone</th>';
$html .= '<th style="width: 210px">E-mail</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody style="font-size: 12px">';
foreach ($adms as $adm):
    $html .= '<tr>';
    $html .= '<td>'.ucwords($adm['nome']).'</td>';
    $html .= '<td>'.$adm['login'].'</td>';
    $html .= '<td>'.$adm['telefone'].'</td>';
    $html .= '<td>'.$adm['email'].'</td>';
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
