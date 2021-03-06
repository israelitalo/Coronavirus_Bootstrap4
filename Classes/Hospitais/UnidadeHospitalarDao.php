<?php
    namespace Classes\Hospitais;
    //require_once 'Classes/Conexao/Conexao.php';
    require_once __DIR__ . "/../../vendor/autoload.php";

    use Classes\Conexao\Conexao;
    class UnidadeHospitalarDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function getIdHospital($idUsuario){
            $sql = $this->pdo->prepare("SELECT id_hospital FROM usuario WHERE usuario.id = :idUsuario");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $idHospital = $sql->fetch();
            }
            return $idHospital['id_hospital'];
        }

        public function getHospital($idHospital){
            $array = array();
            $sql = $this->pdo->prepare("SELECT * FROM hospital WHERE hospital.id = :idHospital");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetch();
            }
            return $array;
        }

        public function getAllHospitaisPaginacao($p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->query("SELECT * FROM hospital ORDER BY nome LIMIT $p, $qtPaginas");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getAllHospitais(){
            $array = array();
            $sql = $this->pdo->query("SELECT * FROM hospital ORDER BY nome ");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function countHospitaisComLike($busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM hospital WHERE nome LIKE '%".$busca."%'");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function getHospitalLike($busca, $p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->prepare("SELECT * FROM hospital WHERE nome LIKE '%".$busca."%' ORDER BY nome LIMIT $p, $qtPaginas");
            $sql->bindValue(":busca", $busca);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHospitalLikeRel($busca){
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

        public function alterarHospital($idHospital, $nome, $cnpj, $telefone, $rua, $numero, $bairro, $cidade, $estado, $cep){
            $sql = $this->pdo->prepare("UPDATE hospital SET nome = :nome, cnpj = :cnpj, telefone = :telefone, rua = :rua,
                                            numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep
                                            WHERE id = :idHospital");
            $sql->bindValue(":idHospital", $idHospital);
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
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM hospital");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countHospitaisEmUsuario($idHospital){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM usuario u, hospital h WHERE h.id = u.id_hospital AND h.id = :idHospital");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $array = $sql->fetch();
            }
        }

        public function excluirHospital($idHospital){
            $sql = $this->pdo->prepare("DELETE FROM hospital WHERE id = :id");
            $sql->bindValue(":id", $idHospital);
            $sql->execute();
            return true;
        }

        public function getUsuarioHospital($idHospital){
            $sql = $this->pdo->prepare("SELECT u.nome FROM usuario u, hospital h WHERE u.id_hospital = h.id AND h.id = :id");
            $sql->bindValue(":id", $idHospital);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $nome = $sql->fetch();
            }
        }

        public function getMaiorCasosCorona(){
            $sql = $this->pdo->query("SELECT id_hospital, 
                                                  (select nome from hospital where hospital.id = historico.id_hospital)
                                                  as hospital 
                                                  FROM historico, hospital GROUP BY id_hospital ORDER BY count(*) DESC LIMIT 1");
            if($sql->rowCount() > 0){
                return $hospital = $sql->fetch();
            }
        }

        public function countObitos(){
            $sql = $this->pdo->query("SELECT COUNT(*) AS total FROM paciente WHERE paciente.vida = 2");
            $sql->execute();
            if($sql->rowCount() > 0){
                return $total = $sql->fetch();
            }
        }

        public function countHospitaisHistorico($idHospital){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM historico WHERE id_hospital = :idHospital");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->execute();
            if($sql->rowCount() > 0){
                return $total = $sql->fetch();
            }
        }

        public function getHospitalForUser($idUsuario){
            $sql = $this->pdo->prepare("SELECT * FROM hospital h, usuario u WHERE u.id = :id AND h.id = u.id_hospital");
            $sql->bindValue(":id", $idUsuario);
            $sql->execute();
            if($sql->rowCount() > 0){
                return $hospital = $sql->fetchAll();
            }
        }

    }

?>
