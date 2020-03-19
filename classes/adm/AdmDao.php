<?php
    require_once 'classes/conexao/Conexao.php';
    class AdmDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function login($login, $senha){

            $sql = $this->pdo->prepare("SELECT id FROM adm WHERE login = :login AND senha = :senha");
            $sql->bindValue(":login", $login);
            $sql->bindValue(":senha", $senha);
            $sql->execute();

            if($sql->rowCount() > 0){
                $dados = $sql->fetch();
                $_SESSION['id_adm'] = $dados['id'];
                return true;
            }else{
                return false;
            }
        }

        public function getNomeAdm($idAdm){
            $sql = $this->pdo->prepare("SELECT nome FROM adm WHERE id = :idAdm");
            $sql->bindValue(":idAdm", $idAdm);
            $sql->execute();

            if($sql->rowCount() > 0){
                $nomeAdm = $sql->fetch();
                return $nomeAdm['nome'];
            }
        }

    }

?>
