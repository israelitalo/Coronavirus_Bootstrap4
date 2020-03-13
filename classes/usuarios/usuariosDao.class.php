<?php
    require_once 'classes/conexao/conexao.class.php';
    class UsuarioDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function login($login, $senha){
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

        /*public function getUsuarioLike($busca){
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
        }*/

        public function getIdUserEmail($email){
            $sql = $this->pdo->prepare("SELECT id FROM usuario WHERE email = :email");
            $sql->bindValue(":email", $email);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $id = $sql->fetch();
            }
        }

        public function addUsuario($nome, $login, $email, $senha, $unidadeHospitalar, $telefone){
            /*O ativo deve ser 1 para o usuário conseguir acessar o sistema.
              Quando a função de enviar e-mail para validar cadastro de usuários, o o insert abaixo será com ativo = 0.
            */
            $sql= $this->pdo->prepare("INSERT INTO usuario SET ativo = '1', nome = :nome, login = :login, email = :email, senha = :senha, 
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

        public function alterarUsuario($id, $hospital, $nome, $login, $email, $telefone, $senha){
            $sql = $this->pdo->prepare("UPDATE usuario SET nome = :nome, login = :login, email = :email, senha = :senha,
                                                    id_hospital = :id_hospital, telefone = :telefone WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":login", $login);
            $sql->bindValue(":email", $email);
            $sql->bindValue(":senha", $senha);
            $sql->bindValue(":id_hospital", $hospital);
            $sql->bindValue(":telefone", $telefone);
            $sql->execute();
            return true;
        }

        public function updateActiveUser($id){
            $sql = $this->pdo->prepare("UPDATE usuario SET ativo = '1' WHERE MD5(id) = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();
        }

        public function getUsuario($id){
            $sql = $this->pdo->prepare("SELECT * FROM usuario WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $usuario = $sql->fetch();
            }
        }

        public function excluirUsuario($id){
            $sql = $this->pdo->prepare("SELECT * FROM usuario WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $sql = $this->pdo->prepare("DELETE FROM usuario WHERE id = :id");
                $sql->bindValue(":id", $id);
                $sql->execute();

                return true;
            }
        }

        public function getAllUsuariosPaginacao($p, $qtPaginas){
            $array = array();

            $sql = $this->pdo->query("SELECT *,
                                                (select nome from hospital where hospital.id = usuario.id_hospital)
                                                as hospital
                                                FROM usuario ORDER BY nome LIMIT $p, $qtPaginas");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function countUsuarios(){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM usuario");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countUsuariosComLike($busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM usuario WHERE nome LIKE '%".$busca ."%'");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function getUsuarioLike($busca, $p, $qtPaginas){
            $array = array();

            $sql = $this->pdo->prepare("SELECT *,
                                                (select nome from hospital where hospital.id = usuario.id_hospital)
                                                as hospital
                                                FROM usuario WHERE nome LIKE '%".$busca."%' ORDER BY nome LIMIT $p, $qtPaginas");
            $sql->bindValue(":busca", $busca);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

    }

?>
