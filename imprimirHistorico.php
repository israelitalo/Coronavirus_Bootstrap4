<?php

    session_start();
    if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    use Dompdf\Dompdf;

    require_once __DIR__ . '/vendor/autoload.php';
    require_once 'classes/historicoPaciente/HistoricoPacientes.php';
    require_once 'classes/historicoPaciente/HistoricoPacientesDao.php';

    $historicos = new HistoricoPaciente();
    $historicoDao = new HistoricoPacienteDao();

    if(isset($_SESSION['id_adm'])){
        $historicos = $historicoDao->getAllHistoricos();
    }elseif(isset($_SESSION['id_usuario'])){
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $historicos = $historicoDao->getHistoricoPorUsuario($idUsuario);
    }

    // DEFINE O FUSO HORARIO COMO O HORARIO DE Recife
    date_default_timezone_set('America/Recife');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    $data = date('d/m/Y H:i:s', time());

    //$hora = date('H:i:s', strtotime($dataAtual));

    //ob_start();

    $html = '
        <head>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        </head>
    ';
    $html .= '<div class="text-right" style="margin-top:30px; font-size: 12px">
                <p style="margin: 0">Data:'.$data.'</p>
                <p style="margin: 0">Todas os Hospitais cadastrados</p>
              </div>';
    $html .= '<div class="h3 card-header text-left">Covid-19: Histórico de Pacientes</div>';
    $html .= '<table class="table table-light table-borderless">';
        $html .= '<thead class="thead-dark" style="font-size: 14px">';
            $html .= '<tr>';
            $html .= '<th style="width: 25%">Paciente</th>';
            $html .= '<th style="width: 20%">Hospital</th>';
            $html .= '<th style="width: 20%">Diagnóstico</th>';
            $html .= '<th style="width: 20%">Entrada</th>';
            $html .= '<th style="width: 20%">Saída</th>';
            $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody style="font-size: 12px">';
        foreach ($historicos as $historico):
            $html .= '<tr>';
            $html .= '<td>'.ucwords($historico['paciente']).'</td>';
            $html .= '<td>'.ucwords($historico['hospital']).'</td>';
            $html .= '<td>'.ucwords($historico['diagnostico']).'</td>';
            $html .= '<td>'.date('d/m/Y', strtotime($historico['data_entrada'])).'</td>';
            if($historico['data_saida'] == null){
                $historico['data_saida'] = 'Aguardando saída';
                $html .= '<td>'.$historico['data_saida'].'</td>';
            }else{
                $html .= '<td>'.date('d/m/Y', strtotime($historico['data_saida'])).'</td>';
            }
            $html .= '</tr>';
        endforeach;
        $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<div style="position: fixed; bottom: 0; width: 100%; background-color: #cccccc; padding-bottom: 3px">
                <div style="font-size: 11px">
                    Relatório emitido pela equipe CvSoftware
                </div>
              </div>';

    //$html.=ob_get_clean();

    $domPdf = new Dompdf();

    $domPdf->loadHtml($html);

    $domPdf->setPaper("A4");

    $domPdf->render();

    $domPdf->stream("file.pdf", ["Attachment" => false]);



?>
