<?php

    session_start();
    if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    use Dompdf\Dompdf;
    use Classes\Pacientes\Paciente;
    use Classes\Pacientes\PacienteDao;
    /*require 'Classes/Pacientes/Paciente.php';
    require 'Classes/Pacientes/PacienteDao.php';*/

    $pd = new PacienteDao();
    $pacientes = new Paciente();

    if(isset($_SESSION['id_adm'])){
        $pacientes = $pd->getAllPacientes();
    }elseif(isset($_SESSION['id_usuario'])){
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $pacientes = $pd->getAllPacienteForUserLogadoRel($idUsuario);
    }

    if(isset($_GET['busca']) && $_GET['busca'] != ''){
        $busca = addslashes($_GET['busca']);
        if(isset($_SESSION['id_adm'])){
            $pacientes = $pd->getPacienteLikeRel($busca);
        }elseif(isset($_SESSION['id_usuario'])){
            $pacientes = $pd->getPacienteLikeForUserLogadoRel($busca, $idUsuario);
        }
    }

    if(isset($_SESSION['id_usuario'])){
        foreach ($pacientes as $paciente):
            $hospital = $paciente['hospital'];
        endforeach;
    }else{
        $hospital = "Todas os hospitais cadastrados";
    }

    // DEFINE O FUSO HORARIO COMO O HORARIO DE Recife
    date_default_timezone_set('America/Recife');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    $data = date('d/m/Y H:i:s', time());

    $html = '
            <head>
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <title>Relatório de pacientes</title>
            <style>
                .pagenum:before {content: counter(page);}
                footer .pagenum:before {content: counter(page);}
            </style>
            </head>
        ';
    $html .= '<div class="text-right" style="margin-top:30px; font-size: 12px">
                    <p style="margin: 0">Data:'.$data.'</p>
                    <p style="margin: 0">'.$hospital.'</p>
                    <p style="margin: 0">filtro: '.$_GET['busca'].'</p>
                  </div>';
    $html .= '<div class="h3 card-header text-left">Covid-19: Pacientes</div>';
    $html .= '<table class="table table-light table-borderless">';
    $html .= '<thead class="thead-dark" style="font-size: 14px">';
    $html .= '<tr>';
    $html .= '<th style="width: 30%">Nome</th>';
    $html .= '<th style="width: 30%">Hospital</th>';
    $html .= '<th style="width: 20%">CPF</th>';
    $html .= '<th style="width: 15%">Gênero</th>';
    $html .= '<th style="width: 15%">Status</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody style="font-size: 12px">';
    foreach ($pacientes as $paciente):
        $html .= '<tr>';
        $html .= '<td>'.ucwords($paciente['nome']).'</td>';
        $html .= '<td>'.ucwords($paciente['hospital']).'</td>';
        $html .= '<td>'.ucwords($paciente['cpf']).'</td>';
        if($paciente['sexo'] == 'm'){
            $paciente['sexo'] = 'Masculino';
            $html .= '<td>'.$paciente['sexo'].'</td>';
        }else{
            $paciente['sexo'] = 'Feminino';
            $html .= '<td>'.$paciente['sexo'].'</td>';
        }
        if($paciente['vida'] == 1){
            $paciente['vida'] = 'Vivo';
            $html .= '<td>'.ucwords($paciente['vida']).'</td>';
        }else{
            $paciente['vida'] = 'Óbito';
            $html .= '<td>'.ucwords($paciente['vida']).'</td>';
        }
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
