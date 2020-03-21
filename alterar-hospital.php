<?php
session_start();
if(empty($_SESSION['id_adm'])){
    ?>
    <script type="text/javascript">window.location.href="sair.php";</script>
    <?php
}

    require_once 'pages/header.php';

if(isset($_GET['id']) && !empty($_GET['id'])){

    require 'classes/hospitais/UnidadeHospitalar.php';
    require 'classes/hospitais/UnidadeHospitalarDao.php';

    $hospital = new UnidadeHospitalar();
    $hospital->setId(addslashes($_GET['id']));
    $hd = new UnidadeHospitalarDao();

    $info = $hd->getHospital($hospital->getId());

    if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['cnpj']) && !empty($_POST['cnpj'])
        && isset($_POST['telefone']) && !empty($_POST['telefone']) && isset($_POST['rua']) && !empty($_POST['rua'])
        && isset($_POST['numero']) && !empty($_POST['numero']) && isset($_POST['bairro']) && !empty($_POST['bairro'])
        && isset($_POST['cidade']) && !empty($_POST['cidade']) && isset($_POST['estado']) && !empty($_POST['estado'])
        && isset($_POST['cep']) && !empty($_POST['cep'])){

        $hospital->setNome(addslashes($_POST['nome']));
        $hospital->setCnpj(addslashes($_POST['cnpj']));
        $hospital->setTelefone(addslashes($_POST['telefone']));
        $hospital->setRua(addslashes($_POST['rua']));
        $hospital->setNumero(addslashes($_POST['numero']));
        $hospital->setBairro(addslashes($_POST['bairro']));
        $hospital->setCidade(addslashes($_POST['cidade']));
        $hospital->setEstado(addslashes($_POST['estado']));
        $hospital->setCep(addslashes($_POST['cep']));

        if($hd->alterarHospital($hospital->getId(), $hospital->getNome(), $hospital->getCnpj(), $hospital->getTelefone(),
            $hospital->getRua(), $hospital->getNumero(), $hospital->getBairro(), $hospital->getCidade(), $hospital->getEstado(),
            $hospital->getCep())){

            $_SESSION['msg'] = "Hospital alterado com sucesso.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
            <?php
        }
        else{
            $_SESSION['msg'] = "Erro ao tentar alterar Hospital.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
            <?php
        }

    }

}

?>
<body>
<div class="container">
    <div style="margin-top: 20px; margin-bottom: 20px">
        <h2><span class="badge badge-secondary">Alterar Hospitais</span></h2>
    </div>
    <div class="col">
        <form class="form-group" method="POST">
            <div>
                <label class="col-form-label-lg" for="nome">Hospital</label>
                <input autocomplete="off" value="<?php echo $info['nome']?>" type="text" name="nome" class="form-control" id="nomeHospital" placeholder="Nome do Hospital" required>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="cnpj">CNPJ</label>
                    <input type="text" value="<?php echo $info['cnpj']?>" autocomplete="off" class="form-control" name="cnpj" id="cnpjHospital" placeholder="Digite apenas os números do cnpj">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="telefone">Telefone</label>
                    <input type="text" value="<?php echo $info['telefone']?>" autocomplete="off" name="telefone" class="form-control" placeholder="(81)99999999">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="cep">CEP</label>
                    <input type="text" value="<?php echo $info['cep']?>" autocomplete="off" class="form-control" name="cep" id="cepHospital" required placeholder="Digite aqui o CEP sem hífen">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="rua">Rua</label>
                    <input type="text" value="<?php echo $info['rua']?>" autocomplete="off" name="rua" class="form-control" id="ruaHospital" required placeholder="Digite a Rua ou Av.">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="numero">Número</label>
                    <input type="text" value="<?php echo $info['numero']?>" autocomplete="off" class="form-control" name="numero" id="numeroHospital" required placeholder="Número do estabelecimento">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="bairro">Bairro</label>
                    <input type="text" value="<?php echo $info['bairro']?>" autocomplete="off" name="bairro" class="form-control" id="bairroHospital" required placeholder="Bairro da unidade">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="col-form-label-lg" for="cidade">Cidade</label>
                    <input type="text" value="<?php echo $info['cidade']?>" autocomplete="off" class="form-control" name="cidade" id="cidadeHospital" required placeholder="Cidade da unidade">
                </div>
                <div class="col">
                    <label class="col-form-label-lg" for="estado">Estado</label>
                    <input type="text" value="<?php echo $info['estado']?>" autocomplete="off" class="form-control" name="estado" id="estadoHospital" required placeholder="Digite aqui a UF do estado">
                </div>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <button class="btn btn-primary" type="submit">Alterar</button>
            </div>
        </form>
    </div>
</div>
</body>
