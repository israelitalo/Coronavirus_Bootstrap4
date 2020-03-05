<?php
    require_once 'classes/conexao/conexao.class.php';
    class UnidadeHospitalarDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function getIdHospital($idUsuario){
            //global $pdo;
            $sql = $this->pdo->prepare("SELECT id_hospital FROM usuario WHERE usuario.id = :idUsuario");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $idHospital = $sql->fetch(PDO::FETCH_ASSOC);
            }
            return $idHospital['id_hospital'];
        }

        public function getHospital($idHospital){
            //global $pdo;
            $array = array();

            $sql = $this->pdo->prepare("SELECT * FROM hospital WHERE hospital.id = :idHospital");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetch();
            }
            return $array;
        }

        public function getAllHospitais(){
            //global $pdo;
            $array = array();

            $sql = $this->pdo->query("SELECT * FROM hospital ORDER BY nome");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHospitalLike($busca){
            //global $pdo;
            $array = array();

            $sql = $this->pdo->prepare("SELECT * FROM hospital WHERE nome LIKE '%".$busca."%' ORDER BY nome");
            $sql->bindValue(":busca", $busca);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHospitalUserLogado($id_usuario){
            //global $pdo;
            $sql = $this->pdo->prepare("SELECT *,
                                            (select nome from hospital where hospital.id = usuario.id_hospital)
                                            as hospital
                                            FROM usuario WHERE id = :idUsuario");
            $sql->bindValue(":idUsuario", $id_usuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $hospital = $sql->fetch();
            }

        }

        public function addHospital($nome, $cnpj, $telefone, $rua, $numero, $bairro, $cidade, $estado, $cep){
            //global $pdo;
            $sql = $this->pdo->prepare("INSERT INTO hospital SET nome = :nome, cnpj = :cnpj, telefone = :telefone, rua = :rua,
                                            numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep");
            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":cnpj", $cnpj);
            $sql->bindValue(":telefone", $telefone);
            $sql->bindValue(":rua", $rua);
            $sql->bindValue(":numero", $numero);
            $sql->bindValue(":bairro", $bairro);
            $sql->bindValue(":cidade", $cidade);
            $sql->bindValue(":estado", $estado);
            $sql->bindValue(":cep", $cep);
            $sql->execute();
            return true;
        }

        public function countHospitais(){
            $sql = $this->pdo->prepare("SELECT * FROM hospital");
            $row = $sql->rowCount();
            return $row;
        }

    }

?>
