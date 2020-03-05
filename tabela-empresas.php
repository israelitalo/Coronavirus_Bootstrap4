<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="login.php";</script>
        <?php
    }
    require 'classes/hospitais/unidadeHospitalar.class.php';
    require 'classes/hospitais/unidadeHospitalarDao.class.php';
    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();
    $hospitais = $hd->getAllHospitais();

    //POST busca vem do arquivo gerenciar-unidades.php, através de requisição ajax.
    if(isset($_POST['busca'])){
        $busca = $_POST['busca'];
        $hospitais = $hd->getHospitalLike($busca);
    }

    $countRegistros = $hd->countHospitais();

    $dados = array();

    $requestData = $_REQUEST;

    $columns = array(
        array( '0' => 'nome' ),
        array( '1' => 'cnpj' ),
        array( '2' => 'telefone' ),
        array( '3' => 'cidade' ),
        array( '4' => 'estado' )
    );

?>
    <!-- Corpo de tabela que preenche o corpo da tabela do arquivo gerenciar-unidades.php -->
<?php foreach ($hospitais as $hospital): ?>
    <?php
        $dado = array();
        $dado[] = $hospital['nome'];
        $dado[] = $hospital['cnpj'];
        $dado[] = $hospital['telefone'];
        $dado[] = $hospital['cidade'];
        $dado[] = $hospital['estado'];
        $dados[] = $dado;
    ?>
<?php endforeach; ?>
<?php
    $json_data = array(
          "draw" => intval($requestData['draw']),
          "recordsTotal" => intval($countRegistros),
          "data" => $dados
    );

    echo json_encode($json_data);
?>
