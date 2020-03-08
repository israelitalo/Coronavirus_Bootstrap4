<?php

session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

    require 'pages/header.php';
    require 'classes/diagnostico/diagnostico.class.php';
    require 'classes/diagnostico/diagnosticoDao.class.php';
    require 'classes/hospitais/unidadeHospitalar.class.php';
    require 'classes/hospitais/unidadeHospitalarDao.class.php';
    require 'classes/pacientes/pacienteDao.class.php';
    require 'classes/pacientes/paciente.class.php';

    $diagnosticos = new Diagnostico();
    $dd = new DiagnosticoDao();
    $diagnosticos = $dd->getDiagnosticos();
    $pacientes = new Paciente();
    $pd = new PacienteDao();
    $hospitais = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    if(isset($_SESSION['id_usuario'])){
        $idUsuario = addslashes($_SESSION['id_usuario']);
        $hospitais = $hd->getHospitalUserLogado($idUsuario);
        $pacientes = $pd->getPacienteForUserLogado($idUsuario);
    }elseif(isset($_SESSION['id_adm'])){
        $hospitais = $hd->getAllHospitais();
        $pacientes = $pd->getPacientes();
    }

?>

<section class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Cadastro de Histórico de Pacientes</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg = "Histórico de paciente cadastrado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg = "Verifique se o paciente em questão veio a óbito."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg = "Preencha todos os campos e tente realizar o registro novamente."){
            ?>
            <div class="alert alert-warning"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
    <div class="col">
        <form class="form-group" method="POST" action="cadastrar-historico-bd.php">
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="hospital">Unidade Hospitalar</label>
                    <select class="form-control" name="hospital" id="hospitalhistorico" required>
                        <option></option>
                        <?php if(isset($_SESSION['id_adm'])): ?>
                            <?php foreach ($hospitais as $hospital): ?>
                                <option value="<?php echo $hospital['id'];?>"><?php echo ucwords($hospital['nome']);?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="<?php echo $hospitais['id_hospital']; ?>"><?php echo ucwords($hospitais['hospital']); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="paciente">Paciente</label>
                    <select class="form-control" name="paciente" id="pacientehistorico" required>
                        <option></option>
                        <!--Caso o usuário logado seja adm, todos os pacientes aparecerão para ele.-->
                        <?php if(isset($_SESSION['id_adm'])): ?>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>"><?php echo ucwords($paciente['nome']); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!--Caso o usuário logado não seja adm, apenas os pacientes do seu hospital aparecerão para ele.-->
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>"><?php echo ucwords($paciente['nome']);?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="diagnostico">Diagnóstico</label>
                    <select class="form-control" name="diagnostico" required>
                        <option></option>
                        <?php foreach ($diagnosticos as $diagnostico): ?>
                            <option value="<?php echo $diagnostico['id']; ?>"><?php echo ucwords($diagnostico['status']);?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="entrada">Data de Entrada</label>
                    <input class="form-control" type="date" name="entrada" id="dataEntrada" required>
                </div>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Adicionar</button>
            </div>
        </form>
    </div>
</section>
