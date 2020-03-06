<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        require 'sair.php';
    }

    require 'pages/header.php';
    require 'classes/hospitais/unidadeHospitalar.class.php';
    require 'classes/hospitais/unidadeHospitalarDao.class.php';

    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    if(isset($_GET['busca'])){
        $busca = addslashes($_GET['busca']);
        $hospitais = $hd->getHospitalLike($busca);
    }else{
        $hospitais = $hd->getAllHospitais();
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
        <h2><span class="badge badge-secondary">Gerenciar Hospitais</span></h2>
    </div>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-success" href="cadastrar-hospital.php" role="button">Adicionar</a>
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
            <caption>Hospitais Cadastrados</caption>
            <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($hospitais as $hospital): ?>
                <tr>
                    <td style="width: 25%; padding-top: 20px"><?php echo ucwords($hospital['nome']); ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $hospital['cnpj']; ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo $hospital['telefone']; ?></td>
                    <td style="width: 15%; padding-top: 20px"><?php echo ucwords($hospital['cidade']); ?></td>
                    <td style="width: 10%; padding-top: 20px"><?php echo ucwords($hospital['estado']); ?></td>
                    <td class="text-center" style="width: 20%>">
                        <a class="btn btn-outline-warning" href="alterar-empresa.php?id=<?php echo $hospital['id'] ;?>"><img width="26" height="26" onmouseover="alterarAtivo($(this))" id="icone-editar" src="assets/images/icones/alterar.png"></a>
                        <a class="btn btn-outline-danger" href="excluir-empresa.php?id=<?php echo $hospital['id'] ;?>"><img id="icone-excluir" src="assets/images/icones/excluir.png"></a>
                        <a class="btn btn-outline-info" href="#"><img id="icone-lista" src="assets/images/icones/lista.png"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!--Tabela a ser usada com o DataTable
    <table id="listar-hospitais" class="display table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Name</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        </tfoot>
    </table>
    -->
</div>
