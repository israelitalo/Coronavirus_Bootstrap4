<?php
    require_once 'classes/conexao/conexao.class.php';
    class PacienteDao {

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function getAllPacientesPaginacao($p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->query("SELECT *,
                                            (select nome from hospital where paciente.id_hospital = hospital.id)
                                            as hospital
                                            FROM paciente ORDER BY nome LIMIT $p, $qtPaginas");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getAllPacientes(){
            $array = array();
            $sql = $this->pdo->query("SELECT *,
                                            (select nome from hospital where paciente.id_hospital = hospital.id)
                                            as hospital
                                            FROM paciente ORDER BY nome");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        //Método para Pacientes vivos, ou seja, com vida = 1
        public function getPacientes(){
            $array = array();
            $sql = $this->pdo->query("SELECT *,
                                            (select nome from hospital where paciente.id_hospital = hospital.id)
                                            as hospital
                                            FROM paciente WHERE paciente.vida = 1 ORDER BY nome");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getPaciente($idPaciente){
            $array = array();
            $sql = $this->pdo->prepare("SELECT * FROM paciente WHERE id = :id");
            $sql->bindValue(":id", $idPaciente);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetch();
            }
            return $array;
        }

        public function getPacienteLike($busca, $p, $qtPaginas){
            $array = array();

            $sql = $this->pdo->prepare("SELECT *,
                                            (select nome from hospital where paciente.id_hospital = hospital.id)
                                            as hospital
                                            FROM paciente WHERE nome LIKE '%".$busca."%' ORDER BY nome LIMIT $p, $qtPaginas");
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getPacienteLikeForUserLogado($busca, $idUsuario, $p, $qtPaginas){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT p.id, p.id_hospital, p.nome, p.cpf, p.rua, p.numero, p.bairro, p.cidade,
                                            p.estado, p.cep, p.telefone, p.sexo, p.data_nascimento, p.vida,
                                            (select nome from hospital where p.id_hospital = hospital.id) as hospital 
                                            FROM paciente p, usuario u
                                            WHERE p.id_hospital = u.id_hospital AND p.nome LIKE '%".$busca."%'
                                            AND u.id = :idUsuario ORDER BY p.nome LIMIT $p, $qtPaginas");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getAllPacienteForUserLogado($idUsuario, $p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->prepare("SELECT p.id, p.id_hospital, p.nome, p.cpf, p.rua, p.numero, p.bairro, p.cidade,
                                            p.estado, p.cep, p.telefone,p.sexo, p.data_nascimento, p.vida,
                                            (select nome from hospital where p.id_hospital = hospital.id) as hospital 
                                            FROM paciente p, usuario u 
                                            WHERE p.id_hospital = u.id_hospital
                                            AND u.id = :idUsuario ORDER BY p.nome LIMIT $p, $qtPaginas");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getPacienteForUserLogado($idUsuario){
            $array = array();
            $sql = $this->pdo->prepare("SELECT p.id, p.id_hospital, p.nome, p.cpf, p.rua, p.numero, p.bairro, p.cidade,
                                            p.estado, p.cep, p.telefone,p.sexo, p.data_nascimento,
                                            (select nome from hospital where p.id_hospital = hospital.id) as hospital 
                                            FROM paciente p, usuario u 
                                            WHERE p.id_hospital = u.id_hospital AND p.vida = 1
                                            AND u.id = :idUsuario ORDER BY p.nome");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function addPaciente($idHospital, $nome, $cpf, $sexo, $dataNascimento, $rua, $numero, $bairro, $cidade, $estado, $cep, $telefone){
            //global $pdo;
            $sql = $this->pdo->prepare("INSERT INTO paciente SET id_hospital = :idHospital, nome = :nome, cpf = :cpf,
                                            sexo = :sexo, data_nascimento = :dataNascimento, rua = :rua, numero = :numero, bairro = :bairro, 
                                            cidade = :cidade, estado = :estado, cep = :cep, telefone = :telefone, vida = 1");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":cpf", $cpf);
            $sql->bindValue(":sexo", $sexo);
            $sql->bindValue(":dataNascimento", $dataNascimento);
            $sql->bindValue(":rua", $rua);
            $sql->bindValue(":numero", $numero);
            $sql->bindValue(":bairro", $bairro);
            $sql->bindValue(":cidade", $cidade);
            $sql->bindValue(":estado", $estado);
            $sql->bindValue(":cep", $cep);
            $sql->bindValue(":telefone", $telefone);
            $sql->execute();
            return true;
        }

        public function alterarPaciente( $idPaciente, $idHospital, $nome, $cpf, $sexo, $dataNascimento, $rua, $numero, $bairro, $cidade, $estado, $cep, $telefone){
            $sql = $this->pdo->prepare("UPDATE paciente SET id_hospital = :idHospital, nome = :nome, cpf = :cpf, sexo = :sexo,
                                            data_nascimento = :dataNascimento, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, 
                                            estado = :estado, cep = :cep, telefone = :telefone WHERE id = :id");
            $sql->bindValue(":id", $idPaciente);
            $sql->bindValue(":idHospital", $idHospital);
            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":cpf", $cpf);
            $sql->bindValue(":sexo", $sexo);
            $sql->bindValue(":dataNascimento", $dataNascimento);
            $sql->bindValue(":rua", $rua);
            $sql->bindValue(":numero", $numero);
            $sql->bindValue(":bairro", $bairro);
            $sql->bindValue(":cidade", $cidade);
            $sql->bindValue(":estado", $estado);
            $sql->bindValue(":cep", $cep);
            $sql->bindValue(":telefone", $telefone);

            if($sql->execute()){
                return true;
            }
            else{
                return false;
            }
        }

        public function excluirPaciente($idPaciente){
            $sql = $this->pdo->prepare("DELETE FROM paciente WHERE id = :id");
            $sql->bindValue(":id", $idPaciente);
            $sql->execute();
            return true;
        }

        public function countPacientesEmHistorico($idPaciente){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM historico h, paciente p WHERE h.id_paciente = p.id AND p.id = :idPaciente");
            $sql->bindValue(":idPaciente", $idPaciente);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $array = $sql->fetch();
            }
        }

        public function countPacientes(){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM paciente");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countPacientesUsuario($idUsuario){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM paciente p, usuario u 
                                                WHERE p.id_hospital = u.id_hospital
                                                AND u.id = :idUsuario");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countPacientesUsuarioLike($idUsuario, $busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM paciente p, usuario u 
                                                WHERE p.nome LIKE '%".$busca."%' AND p.id_hospital = u.id_hospital
                                                AND u.id = :idUsuario");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countPacientesComLike($busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM paciente WHERE nome LIKE '%".$busca."%'");
            $sql->execute();
            return $total = $sql->fetch();
        }

    }

?>
