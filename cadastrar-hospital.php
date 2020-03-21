<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once 'pages/header.php';

?>
<body>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Cadastrar Hospitais</span></h2>
    </div>
    <?php
    if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
        $msg = $_SESSION['msg'];
        if($msg == "Hospital cadastrado com sucesso."){
            ?>
            <div class="alert alert-success"><?php echo $msg;?></div>
            <?php
        }elseif($msg == "Erro ao tentar cadastrar hospital."){
            ?>
            <div class="alert alert-danger"><?php echo $msg;?></div>
            <?php
        }
        unset($_SESSION['msg']);
    }
    ?>
    <div class="col">
        <form class="form-group" method="POST" action="cadastrar-hospital-bd.php">
            <div>
                <label class="col-form-label-lg" for="nome">Hospital</label>
                <input autocomplete="off" type="text" name="nome" class="form-control" id="nomeHospital" placeholder="Nome do Hospital" required>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="cnpj">CNPJ</label>
                    <input type="text" autocomplete="off" class="form-control" name="cnpj" id="cnpjHospital" placeholder="Digite apenas os números do cnpj">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input type="text" autocomplete="off" name="telefone" class="form-control" placeholder="(81)99999999">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="cep">CEP</label>
                    <input type="text" autocomplete="off" class="form-control" name="cep" id="cepHospital" required placeholder="Digite aqui o CEP sem hífen">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="rua">Rua</label>
                    <input type="text" autocomplete="off" name="rua" class="form-control" id="ruaHospital" required placeholder="Digite a Rua ou Av.">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="numero">Número</label>
                    <input type="text" autocomplete="off" class="form-control" name="numero" id="numeroHospital" required placeholder="Número do estabelecimento">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="bairro">Bairro</label>
                    <input type="text" autocomplete="off" name="bairro" class="form-control" id="bairroHospital" required placeholder="Bairro da unidade">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="cidade">Cidade</label>
                    <input type="text" autocomplete="off" class="form-control" name="cidade" id="cidadeHospital" required placeholder="Cidade da unidade">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="estado">Estado</label>
                    <input type="text" autocomplete="off" class="form-control" name="estado" id="estadoHospital" required placeholder="Digite aqui a UF do estado">
                </div>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Adicionar</button>
            </div>
        </form>
    </div>
</div>
</body>
