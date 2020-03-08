<?php
session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

require 'pages/header.php';
require 'classes/pacientes/paciente.class.php';
require 'classes/pacientes/pacienteDao.class.php';
require 'classes/hospitais/unidadeHospitalar.class.php';
require 'classes/hospitais/unidadeHospitalarDao.class.php';

if(isset($_GET['id']) && !empty($_GET['id'])){
    $paciente = new Paciente();
    $pd = new PacienteDao();
    $paciente->setId(addslashes($_GET['id']));

    $hospitais = new UnidadeHospitalar();
    $ud = new UnidadeHospitalarDao();
    $hospitais = $ud->getAllHospitais();

    $info = $pd->getPaciente($paciente->getId());

    if(isset($_SESSION['id_usuario'])) {
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $nomeHospital = $ud->getHospitalUserLogado($idUsuario);
    }

    if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['hospital']) && !empty($_POST['hospital'])
        && isset($_POST['cpf']) && !empty($_POST['cpf']) && isset($_POST['cep']) && !empty($_POST['cep'])
        && isset($_POST['cidade']) && !empty($_POST['cidade']) && isset($_POST['estado']) && !empty($_POST['estado'])
        && isset($_POST['data_nascimento']) && !empty($_POST['data_nascimento']) && isset($_POST['sexo']) && !empty($_POST['sexo'])){

        $paciente->setIdHospital(addslashes($_POST['hospital']));
        $paciente->setNome(addslashes($_POST['nome']));
        $paciente->setCpf(addslashes($_POST['cpf']));
        $paciente->setSexo(addslashes($_POST['sexo']));
        $paciente->setDataNascimento(addslashes($_POST['data_nascimento']));
        $paciente->setRua(addslashes($_POST['rua']));
        $paciente->setNumero(addslashes($_POST['numero']));
        $paciente->setBairro(addslashes($_POST['bairro']));
        $paciente->setCidade(addslashes($_POST['cidade']));
        $paciente->setEstado(addslashes($_POST['estado']));
        $paciente->setCep(addslashes($_POST['cep']));
        $paciente->setTelefone(addslashes($_POST['telefone']));

        if($pd->alterarPaciente($paciente->getId(), $paciente->getIdHospital(), $paciente->getNome(), $paciente->getCpf(), $paciente->getSexo(),
            $paciente->getDataNascimento(), $paciente->getRua(), $paciente->getNumero(), $paciente->getBairro(), $paciente->getCidade(),
            $paciente->getEstado(), $paciente->getCep(), $paciente->getTelefone()) == true){
            $_SESSION['msg'] = "Paciente alterado com sucesso.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-pacientes.php";</script>
            <?php
        }else{
            $_SESSION['msg'] = "Erro ao tentar alterar paciente.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-pacientes.php";</script>
            <?php
        }
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de usuário</title>
</head>
<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Alterar Paciente</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST">

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="nome">Nome</label>
                    <input class="form-control" value="<?php echo $info['nome']; ?>" autocomplete="off" type="text" name="nome" id="nomePaciente" required placeholder="Nome do paciente">
                </div>

                <div class="col-4">
                    <label class="col-form-label-lg" for="hospital">Hospital</label>
                    <select class="form-control" name="hospital">
                        <option></option>
                        <?php if(isset($_SESSION['id_adm'])): ?>
                            <?php foreach ($hospitais as $hospital): ?>
                                <option value="<?php echo $hospital['id']; ?>" <?php echo ($info['id_hospital']==$hospital['id'])?'selected="selected"':''?> > <?php echo ucwords($hospital['nome']); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Aqui, caso quem esteja cadastrando seja um usuário comum, deve aparecer apenas o hospital a qual este o usuário é vinculado.-->
                            <option value="<?php echo $nomeHospital['id_hospital']; ?>" <?php echo ($info['id_hospital']==$nomeHospital['id_hospital'])?'selected="seletected"':''?> > <?php echo ucwords($nomeHospital['hospital']);?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-3">
                    <label class="col-form-label-lg" for="sexo">Gênero</label>
                    <select class="form-control" name="sexo">
                        <option></option>
                        <option value="m" <?php echo ($info['sexo']=='m')?'selected="selected"':''; ?> > Masculino</option>
                        <option value="f" <?php echo ($info['sexo']=='f')?'selected="selected"':''; ?> >Feminino</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="cpf">CPF</label>
                    <input class="form-control" value="<?php echo $info['cpf']?>" autocomplete="off" type="text" name="cpf" id="cpfPaciente" required placeholder="Digite o CPF sem pontos e hífen">
                </div>
                <div class="col-4">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input class="form-control" value="<?php echo $info['telefone']?>" name="telefone" placeholder="(81)99999999">
                </div>
                <div class="col-3">
                    <label class="col-form-label-lg" for="data_nascimento">Nascimento</label>
                    <input class="form-control" value="<?php echo $info['data_nascimento']?>" type="date" name="data_nascimento">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="cep">CEP</label>
                    <input class="form-control" value="<?php echo $info['cep']?>" type="text" name="cep" id="cepPaciente" required placeholder="Digite aqui o CEP sem hífen">
                </div>
                <div class="col-4">
                    <label class="col-form-label-lg" for="rua">Rua</label>
                    <input class="form-control" value="<?php echo $info['rua']?>" type="text" name="rua" id="ruaPaciente" required placeholder="Digite a Rua ou Av.">
                </div>
                <div class="col-3">
                    <label class="col-form-label-lg" for="numero">Numero</label>
                    <input class="form-control" value="<?php echo $info['numero']?>" autocomplete="off" type="text" name="numero" id="numeroPaciente" required placeholder="Número da residência">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label class="col-form-label-lg" for="bairro">Bairro</label>
                    <input class="form-control" value="<?php echo $info['bairro']?>" type="text" name="bairro" id="bairroPaciente" required placeholder="Bairro do endereço">
                </div>
                <div class="col-4">
                    <label class="col-form-label-lg" for="cidade">Cidade</label>
                    <input class="form-control" value="<?php echo $info['cidade']?>" type="text" name="cidade" id="cidadePaciente" required placeholder="Cidade do paciente">
                </div>
                <div class="col-3">
                    <label class="col-form-label-lg" for="estado">Estado</label>
                    <input class="form-control" value="<?php echo $info['estado']?>" type="text" name="estado" id="estadoPaciente" required placeholder="Digite aqui a UF do estado">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Alterar</button>
            </div>
        </form>
    </div>
</section>
