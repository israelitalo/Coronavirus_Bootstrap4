<?php
    require_once 'classes/conexao/conexao.class.php';
    class UsuarioDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function login($login, $senha){
            //global $pdo;

            $sql = $this->pdo->prepare("SELECT id FROM usuario WHERE login = :login AND senha = :senha AND ativo = '1'");
            $sql->bindValue(":login", $login);
            $sql->bindValue(":senha", $senha);
            $sql->execute();

            if($sql->rowCount() > 0){
                $dados = $sql->fetch();
                $_SESSION['id_usuario'] = $dados['id'];
                return true;
            }else{
                return false;
            }
        }

        public function getNomeUsuario($idUsuario){
            $sql = $this->pdo->prepare("SELECT nome FROM usuario WHERE id = :idUsuario");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $nomeusuario = $sql->fetch();
                return $nomeusuario['nome'];
            }
        }

        public function getUsuarios(){
            //global $pdo;
            $array = array();

            $sql = $this->pdo->query("SELECT *,
                                        (select nome from hospital where hospital.id = usuario.id_hospital)
                                        as hospital
                                        FROM usuario ORDER BY nome");
            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getUsuarioLike($busca){
            //global $pdo;
            $array = array();

            $sql = $this->pdo->prepare("SELECT *,
                                            (select nome from hospital where hospital.id = usuario.id_hospital) 
                                            as hospital
                                            FROM usuario WHERE nome LIKE '%".$busca."%' ORDER BY nome");
            $sql->bindValue(":busca", $busca);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function addUsuario($nome, $login, $email, $senha, $unidadeHospitalar, $telefone){
            //global $pdo;

            $sql= $this->pdo->prepare("INSERT INTO usuario SET ativo = '0', nome = :nome, login = :login, email = :email, senha = :senha, 
                                            id_hospital = :unidadeHospitalar, telefone = :telefone");

            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":login", $login);
            $sql->bindValue(":email", $email);
            $sql->bindValue(":senha", $senha);
            $sql->bindValue(":unidadeHospitalar", $unidadeHospitalar);
            $sql->bindValue(":telefone", $telefone);
            $sql->execute();
            return true;
        }

    }

?>
