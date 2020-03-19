<?php
session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require 'pages/header.php';
require 'classes/hospitais/UnidadeHospitalar.php';
require 'classes/hospitais/UnidadeHospitalarDao.php';

$hospitais = new UnidadeHospitalar();
$ud = new UnidadeHospitalarDao();
$hospitais = $ud->getAllHospitais();

if(isset($_SESSION['id_usuario'])){
    $idUsuario = addslashes($_SESSION['id_usuario']);
    $nomeHospital = $ud->getHospitalUserLogado($idUsuario);
}

?>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de usuário</title>
</head>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Cadastrar Pacientes</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST" action="cadastrar-paciente-bd.php">

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="nome">Nome</label>
                    <input class="form-control" autocomplete="off" type="text" name="nome" id="nomePaciente" required placeholder="Nome do paciente">
                </div>

                <div class="col-4">
                    <label class="col-form-label-lg" for="hospital">Hospital</label>
                    <select class="form-control" name="hospital">
                        <option></option>
                        <?php if(isset($_SESSION['id_adm'])): ?>
                            <?php foreach ($hospitais as $hospital): ?>
                                <option value="<?php echo $hospital['id']; ?>"><?php echo ucwords($hospital['nome']); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Aqui, caso quem esteja cadastrando seja um usuário comum, deve aparecer apenas o hospital a qual este o usuário é vinculado.-->
                            <option value="<?php echo $nomeHospital['id_hospital']; ?>"><?php echo ucwords($nomeHospital['hospital']);?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-3">
                    <label class="col-form-label-lg" for="sexo">Gênero</label>
                    <select class="form-control" name="sexo">
                        <option></option>
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="cpf">CPF</label>
                    <input class="form-control" autocomplete="off" type="text" name="cpf" id="cpfPaciente" required placeholder="Digite o CPF sem pontos e hífen">
                </div>
                <div class="col-4">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input class="form-control" name="telefone" placeholder="(81)99999999">
                </div>
                <div class="col-3">
                    <label class="col-form-label-lg" for="data_nascimento">Nascimento</label>
                    <input class="form-control" type="date" name="data_nascimento">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="cep">CEP</label>
                    <input class="form-control" type="text" name="cep" id="cepPaciente" required placeholder="Digite aqui o CEP sem hífen">
                </div>
                <div class="col-4">
                    <label class="col-form-label-lg" for="rua">Rua</label>
                    <input class="form-control" type="text" name="rua" id="ruaPaciente" required placeholder="Digite a Rua ou Av.">
                </div>
                <div class="col-3">
                    <label class="col-form-label-lg" for="numero">Numero</label>
                    <input class="form-control" autocomplete="off" type="text" name="numero" id="numeroPaciente" required placeholder="Número da residência">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="bairro">Bairro</label>
                    <input class="form-control" type="text" name="bairro" id="bairroPaciente" required placeholder="Bairro do endereço">
                </div>
                <div class="col-4">
                    <label class="col-form-label-lg" for="cidade">Cidade</label>
                    <input class="form-control" type="text" name="cidade" id="cidadePaciente" required placeholder="Cidade do paciente">
                </div>
                <div class="col-3">
                    <label class="col-form-label-lg" for="estado">Estado</label>
                    <input class="form-control" type="text" name="estado" id="estadoPaciente" required placeholder="Digite aqui a UF do estado">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Adicionar</button>
            </div>
        </form>
    </div>
</section>
