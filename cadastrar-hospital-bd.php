<?php
    session_start();
if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['cnpj']) && !empty($_POST['cnpj'])
    && isset($_POST['telefone']) && !empty($_POST['telefone']) && isset($_POST['rua']) && !empty($_POST['numero'])
    && isset($_POST['bairro']) && !empty($_POST['bairro']) && isset($_POST['cidade']) && !empty($_POST['cidade'])
    && isset($_POST['estado']) && !empty($_POST['estado']) && isset($_POST['cep']) && !empty($_POST['cep'])){

    require 'classes/hospitais/unidadeHospitalar.class.php';
    require 'classes/hospitais/unidadeHospitalarDao.class.php';

    $unidade = new UnidadeHospitalar();
    $ud = new UnidadeHospitalarDao();

    $unidade->setNome(addslashes($_POST['nome']));
    $unidade->setCnpj(addslashes($_POST['cnpj']));
    $unidade->setTelefone(addslashes($_POST['telefone']));
    $unidade->setRua(addslashes($_POST['rua']));
    $unidade->setNumero(addslashes($_POST['numero']));
    $unidade->setBairro(addslashes($_POST['bairro']));
    $unidade->setCidade(addslashes($_POST['cidade']));
    $unidade->setEstado(addslashes($_POST['estado']));
    $unidade->setCep(addslashes($_POST['cep']));

    if($ud->addHospital($unidade->getNome(), $unidade->getCnpj(), $unidade->getTelefone(), $unidade->getRua(), $unidade->getNumero(), $unidade->getBairro(), $unidade->getCidade(), $unidade->getEstado(), $unidade->getCep())){
        $_SESSION['msg'] = "Hospital cadastrado com sucesso.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
        <?php
    }else{
        $_SESSION['msg'] = "Erro ao tentar cadastrar hospital.";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
        <?php
    }

}

?>
