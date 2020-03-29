<?php
    namespace Classes\Adm;
    require_once __DIR__ . "/../../vendor/autoload.php";
    use Classes\Conexao\Conexao;
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

        public function countAdmComLike($busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM adm WHERE nome LIKE '%".$busca ."%'");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function getAdmLike($busca, $p, $qtPaginas){
            $array = array();

            $sql = $this->pdo->prepare("SELECT * FROM adm WHERE nome LIKE '%".$busca."%' ORDER BY nome LIMIT $p, $qtPaginas");
            $sql->bindValue(":busca", $busca);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function countAdm(){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM adm");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function getAllAdmPaginacao($p, $qtPaginas){
            $array = array();

            $sql = $this->pdo->query("SELECT * FROM adm ORDER BY nome LIMIT $p, $qtPaginas");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function excluirAdm($id){
            $sql = $this->pdo->prepare("SELECT * FROM adm WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $sql = $this->pdo->prepare("DELETE FROM adm WHERE id = :id");
                $sql->bindValue(":id", $id);
                $sql->execute();

                return true;
            }
        }

        public function addAdm($nome, $login, $senha, $telefone, $email){
            $sql= $this->pdo->prepare("INSERT INTO adm SET nome = :nome, login = :login, email = :email, senha = :senha, 
                                            telefone = :telefone");

            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":login", $login);
            $sql->bindValue(":email", $email);
            $sql->bindValue(":senha", $senha);
            $sql->bindValue(":telefone", $telefone);
            $sql->execute();

            return true;
        }

        public function getAdm($id){
            $sql = $this->pdo->prepare("SELECT * FROM adm WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $usuario = $sql->fetch();
            }
        }

        public function alterarAdm($id, $nome, $login, $senha, $telefone, $email){
            $sql = $this->pdo->prepare("UPDATE adm SET nome = :nome, login = :login, email = :email, senha = :senha,
                                                  telefone = :telefone WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":login", $login);
            $sql->bindValue(":email", $email);
            $sql->bindValue(":senha", $senha);
            $sql->bindValue(":telefone", $telefone);
            $sql->execute();
            return true;
        }

        public function getAdmLikeRel($busca){
            $array = array();

            $sql = $this->pdo->prepare("SELECT * FROM adm WHERE nome LIKE '%".$busca."%' ORDER BY nome");
            $sql->bindValue(":busca", $busca);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getAllAdm(){
            $array = array();

            $sql = $this->pdo->query("SELECT * FROM adm ORDER BY nome");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

    }

?>
