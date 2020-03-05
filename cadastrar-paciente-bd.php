<?php
session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require 'classes/pacientes/paciente.class.php';
require 'classes/pacientes/pacienteDao.class.php';

if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['hospital']) && !empty($_POST['hospital'])
    && isset($_POST['cpf']) && !empty($_POST['cpf']) && isset($_POST['cep']) && !empty($_POST['cep'])
    && isset($_POST['cidade']) && !empty($_POST['cidade']) && isset($_POST['estado']) && !empty($_POST['estado'])){

    $paciente = new Paciente();
    $pd = new PacienteDao();

    $paciente->setIdHospital(addslashes($_POST['hospital']));
    $paciente->setNome(addslashes($_POST['nome']));
    $paciente->setCpf(addslashes($_POST['cpf']));
    $paciente->setRua(addslashes($_POST['rua']));
    $paciente->setNumero(addslashes($_POST['numero']));
    $paciente->setBairro(addslashes($_POST['bairro']));
    $paciente->setCidade(addslashes($_POST['cidade']));
    $paciente->setEstado(addslashes($_POST['estado']));
    $paciente->setCep(addslashes($_POST['cep']));
    $paciente->setTelefone(addslashes($_POST['telefone']));

    if($pd->addPaciente($paciente->getIdHospital(), $paciente->getNome(), $paciente->getCpf(), $paciente->getRua(), $paciente->getNumero(), $paciente->getBairro(), $paciente->getCidade(), $paciente->getEstado(), $paciente->getCep(), $paciente->getTelefone()) == true){
        $_SESSION['msg'] = "Paciente cadastrado com sucesso.";
        header('Location: cadastrar-paciente.php');
        exit;
    }else{
        $_SESSION['msg'] = "Erro ao cadastrar paciente.";
        header('Location: cadastrar-paciente.php');
        exit;
    }
}
else{
    $_SESSION['msg'] = "Preencha todos os campos e tente realizar o registro novamente.";
    header('Location: cadastrar-paciente.php');
    exit;
}

?>