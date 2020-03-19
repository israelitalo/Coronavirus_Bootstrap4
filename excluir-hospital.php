<?php
    session_start();
    if(empty($_SESSION['id_adm']) && empty($_SESSION['id_usuario'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    if(empty($_GET['id'])){
        ?>
        <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
        <?php
    }

    require 'classes/hospitais/UnidadeHospitalar.php';
    require 'classes/hospitais/UnidadeHospitalarDao.php';

    $hospital = new UnidadeHospitalar();
    $hd = new UnidadeHospitalarDao();

    $hospital->setId(addslashes($_GET['id']));

    $countHospital = $hd->countHospitaisEmUsuario($hospital->getId());

    if($countHospital['total'] == 0){
        $hd->excluirHospital($hospital->getId());
            $_SESSION['msg'] = "Hospital excluído com sucesso.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
            <?php
    }else{
        $usuarioHospital = $hd->getUsuarioHospital($hospital->getId());
        $_SESSION['usuario_hospital'] = $usuarioHospital['nome'];
        $_SESSION['msg'] = "O hospital não pode ser excluído, pois está vinculádo ao usuário(a) ";
        ?>
        <script type="text/javascript">window.location.href="gerenciar-unidades.php";</script>
        <?php
    }

?>
