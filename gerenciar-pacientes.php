<?php
session_start();
if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    require 'sair.php';
}

require 'pages/header.php';
require 'classes/pacientes/paciente.class.php';
require 'classes/pacientes/pacienteDao.class.php';

$pd = new PacienteDao();
$pacientes = new Paciente();

if(isset($_SESSION['id_adm'])){
    $pacientes = $pd->getPacientes();
}elseif(isset($_SESSION['id_usuario'])){
    $idUsuario = addslashes($_SESSION['id_usuario']);
    $pacientes = $pd->getPacienteForUserLogado($idUsuario);
}

if(isset($_GET['busca']) && $_GET['busca'] != ''){
    $busca = addslashes($_GET['busca']);
    if(isset($_SESSION['id_adm'])){
        $pacientes = $pd->getPacienteLike($busca);
    }elseif(isset($_SESSION['id_usuario'])){
        $pacientes = $pd->getPacienteLikeForUserLogado($busca, $idUsuario);
    }
}

?>
<script>
    /*$(document).ready(function() {
        $('#listar-hospitais').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "tabela-empresas.php",
                "type": "POST"
            }
        });
    });*/
</script>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Gerenciar Pacientes</span></h2>
    </div>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-paciente.php" role="button">Adicionar</a>
        </div>
        <div class="col-6 align-items-end">
            <!--Input de pesquisa da tabela abaixo-->
            <form class="form-group" method="GET">
                <div class="input-group mb-3">
                    <input name="busca" type="search" class="form-control mr-sm-2" placeholder="Busca" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col" style="margin-top: 15px">
        <table class="table table-hover table-light table-borderless table-responsive-lg">
            <caption>Pacientes Cadastrados</caption>
            <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Hospital</th>
                <th>Telefone</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pacientes as $paciente): ?>
                <tr>
                    <td style="width: 25%"><?php echo ucwords($paciente['nome']); ?></td>
                    <td style="width: 18%; padding-top: 20px"><?php echo $paciente['cpf']; ?></td>
                    <td style="width: 22%; padding-top: 20px"><?php echo ucwords($paciente['hospital']); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $paciente['telefone']; ?></td>
                    <td class="text-center" style="width: 20%>">
                        <a class="btn btn-outline-warning" href="alterar-paciente.php?id=<?php echo $paciente['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" href="confirmar_excluir_paciente.php?id=<?php echo $paciente['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" href="#"><img id="icone-lista" src="assets/images/icones/lista.png"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
