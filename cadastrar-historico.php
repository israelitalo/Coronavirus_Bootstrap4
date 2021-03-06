<?php

session_start();

if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

    include_once 'pages/header.php';
    include_once 'pages/navbar.php';

    require_once __DIR__ . '/vendor/autoload.php';

    use Classes\Diagnostico\Diagnostico;
    use Classes\Diagnostico\DiagnosticoDao;
    use Classes\Hospitais\UnidadeHospitalar;
    use Classes\Hospitais\UnidadeHospitalarDao;
    use Classes\Pacientes\Paciente;
    use Classes\Pacientes\PacienteDao;

    /*require 'Classes/Diagnostico/Diagnostico.php';
    require 'Classes/Diagnostico/DiagnosticoDao.php';
    require 'Classes/Hospitais/UnidadeHospitalar.php';
    require 'Classes/Hospitais/UnidadeHospitalarDao.php';
    require 'Classes/Pacientes/PacienteDao.php';
    require 'Classes/Pacientes/Paciente.php';*/

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
        <h2><span class="badge badge-secondary">Cadastrar Histórico</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg == "Histórico de paciente cadastrado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Verifique se o paciente em questão veio a óbito."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Preencha todos os campos e tente realizar o registro novamente."){
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
                <div class="col-6">
                    <label class="col-form-label-lg" for="hospital">Hospital</label>
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
                <div class="col-6">
                    <label class="col-form-label-lg" for="paciente">Paciente</label>
                    <select class="form-control" name="paciente" id="pacientehistorico" required>
                        <option></option>
                        <!--Caso o usuário logado seja adm, todos os pacientes aparecerão para ele.-->

                    </select>
                </div>
            </div>
            <script type="text/javascript">
                $('#hospitalhistorico').change(function () {
                    var idHospital = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "select-pacientes-hospital.php",
                        data:{
                            hospital: idHospital
                        },
                        success: function (retorno) {
                            var html = '';
                            $('#pacientehistorico').html(html);
                            $('#pacientehistorico').append(retorno);
                        }
                    });
                });
            </script>

            <div class="row">
                <div class="col-6">
                    <label class="col-form-label-lg" for="diagnostico">Diagnóstico</label>
                    <select class="form-control" name="diagnostico" required>
                        <option></option>
                        <?php foreach ($diagnosticos as $diagnostico): ?>
                            <option value="<?php echo $diagnostico['id']; ?>"><?php echo ucwords($diagnostico['status']);?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
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
