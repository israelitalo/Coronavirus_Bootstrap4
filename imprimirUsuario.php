<?php

    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    use Dompdf\Dompdf;
    use Classes\Usuarios\Usuarios;
    use Classes\Usuarios\UsuarioDao;
    /*require 'Classes/Usuarios/Usuarios.php';
    require 'Classes/Usuarios/UsuarioDao.php';*/

    $usuarios = new Usuarios();
    $ud = new UsuarioDao();

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $usuarios = $ud->getUsuarioLikeRel($busca);
    }else{
        $usuarios = $ud->getUsuarios();
    }

    // DEFINE O FUSO HORARIO COMO O HORARIO DE Recife
    date_default_timezone_set('America/Recife');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    $data = date('d/m/Y H:i:s', time());

    $html = '
                <head>
                <link rel="stylesheet" href="assets/css/bootstrap.min.css">
                <title>Relatório de usuários</title>
                <style>
                    .pagenum:before {content: counter(page);}
                    footer .pagenum:before {content: counter(page);}
                </style>
                </head>
            ';
    $html .= '<div class="text-right" style="margin-top:30px; font-size: 12px">
                        <p style="margin: 0">Data:'.$data.'</p>
                        <p style="margin: 0">Todos os usuários</p>
                        <p style="margin: 0">filtro: '.$_GET['busca'].'</p>
                      </div>';
    $html .= '<div class="h3 card-header text-left">CvSoftware: Usuários Cadastrados</div>';
    $html .= '<table class="table table-light table-borderless">';
    $html .= '<thead class="thead-dark" style="font-size: 14px">';
    $html .= '<tr>';
    $html .= '<th style="width: 170px">Nome</th>';
    $html .= '<th style="width: 130px">Hospital</th>';
    $html .= '<th style="width: 50px">Status</th>';
    $html .= '<th style="width: 150px">E-mail</th>';
    $html .= '<th style="width: 70px">Telefone</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody style="font-size: 12px">';
    foreach ($usuarios as $usuario):
        $html .= '<tr>';
        $html .= '<td>'.ucwords($usuario['nome']).'</td>';
        $html .= '<td>'.ucwords($usuario['hospital']).'</td>';
        if($usuario['ativo'] == 1){
            $usuario['ativo'] = 'Ativo';
            $html .= '<td>'.$usuario['ativo'].'</td>';
        }else{
            $usuario['ativo'] = 'Inativo';
            $html .= '<td>'.$usuario['ativo'].'</td>';
        }
        $html .= '<td>'.ucwords($usuario['email']).'</td>';
        $html .= '<td>'.ucwords($usuario['telefone']).'</td>';
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
