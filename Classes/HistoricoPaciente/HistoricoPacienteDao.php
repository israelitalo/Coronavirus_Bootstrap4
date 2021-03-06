<?php
    namespace Classes\HistoricoPaciente;
    require_once __DIR__ . '/../../vendor/autoload.php';
    //require_once 'Classes/Conexao/Conexao.php';
    use Classes\Conexao\Conexao;
    class HistoricoPacienteDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        public function getAllHistoricosPaginacao($p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->query("SELECT *,
                                            (select nome from paciente where h.id_paciente = paciente.id)
                                            as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h ORDER BY paciente LIMIT $p, $qtPaginas");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        //Método para retornar o histórico de todos os hospitais, a ser mostrado quando um adm estiver logado.
        public function getAllHistoricos(){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->query("SELECT *,
                                            (select nome from paciente where historico.id_paciente = paciente.id)
                                            as paciente,
                                            (select nome from hospital where historico.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where historico.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico order by paciente");

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        //Método para retornar o histórico através de seu id.
        public function getHistorico($idHistorico){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT *,
                                            (select nome from hospital where hospital.id = historico.id_hospital) as hospital,
                                            (select nome from paciente where historico.id_paciente = paciente.id) as paciente,
                                            (select status from diagnostico_virus where historico.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico WHERE id = :idHistorico");
            $sql->bindValue(":idHistorico", $idHistorico);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetch();
            }
            return $array;
        }

        //Método para mostrar apenas o histórico de pacientes de um hospital específico, verificado pelo id do usuário logado, que retém o hospital em questão.
        public function getHistoricoPorUsuario($idUsuario){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida, 
                                            u.nome, h.motivoalta,
                                            (select nome from paciente where h.id_paciente = paciente.id)
                                            as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, usuario u WHERE h.id_hospital = u.id_hospital AND u.id = :id");
            $sql->bindValue(":id", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        //Método para mostrar apenas o histórico de pacientes de um hospital específico, verificado pelo id do usuário logado, que retém o hospital em questão.
        public function getAllHistoricoForUserLogado($idUsuario, $p, $qtPaginas){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida, 
                                            u.nome, h.motivoalta,
                                            (select nome from paciente where h.id_paciente = paciente.id)
                                            as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, usuario u WHERE h.id_hospital = u.id_hospital AND u.id = :id
                                            ORDER BY paciente LIMIT $p, $qtPaginas");
            $sql->bindValue(":id", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        //Método para mostrar o histórico de pacientes conforme o id do Hospital. *Será uso exclusivo do administrador.
        public function getHistoricoPorHospital($idHospital){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("select h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida, hosp.id
                                            from historico h, hospital hosp where h.id_hospital = hosp.id and hosp.id = :idHospital ORDER BY hosp.id");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHistoricoLikePaginacao($busca, $p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            h.motivoalta,
                                            p.id as id_paciente, p.nome as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, paciente p WHERE h.id_paciente = p.id AND p.nome LIKE '%".$busca."%' ORDER BY p.nome LIMIT $p, $qtPaginas");
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }
        //Método para retornar os históricos através do termo de busca pesquisado.
        public function getHistoricoLike($busca){
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            h.motivoalta,
                                            p.id as id_paciente, p.nome as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, paciente p where h.id_paciente = p.id and p.nome like '%".$busca."%'");
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHistoricoLikeForUserLogado($busca, $idUsuario, $p, $qtPaginas){
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            h.motivoalta,
                                            p.id as id_paciente, p.nome as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico 
                                            FROM historico h, paciente p, usuario u
                                            WHERE h.id_hospital = u.id_hospital AND h.id_paciente = p.id
                                            AND p.nome LIKE '%".$busca."%' AND u.id = :idUsuario ORDER BY p.nome LIMIT $p, $qtPaginas");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHistoricoLikeForUserLogadoSemPag($busca, $idUsuario){
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            h.motivoalta,
                                            p.id as id_paciente, p.nome as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico 
                                            FROM historico h, paciente p, usuario u
                                            WHERE h.id_hospital = u.id_hospital AND h.id_paciente = p.id
                                            AND p.nome LIKE '%".$busca."%' AND u.id = :idUsuario ORDER BY p.nome");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function addHistorico($idHospital, $idPaciente, $idDiagnostico, $dataEntrada){
            $sql = $this->pdo->prepare("SELECT p.vida FROM paciente p WHERE p.id = :idPaciente AND p.vida = 1");
            $sql->bindValue(":idPaciente", $idPaciente);
            $sql->execute();

            if($sql->rowCount() > 0){
                $sql = $this->pdo->prepare("INSERT INTO historico SET id_hospital = :idHospital, id_paciente = :idPaciente,
                                            id_diagnostico = :idDiagnostico, data_entrada = :dataEntrada");
                $sql->bindValue(":idHospital", $idHospital);
                $sql->bindValue(":idPaciente", $idPaciente);
                $sql->bindValue(":idDiagnostico", $idDiagnostico);
                $sql->bindValue(":dataEntrada", $dataEntrada);
                $sql->execute();
                return true;
            }
        return false;
        }

        public function alterarHistorico($idHistorico, $idHospital, $idPaciente, $idDiagnostico, $dataEntrada){
            $sql = $this->pdo->prepare("UPDATE historico SET id_hospital = :idHospital, id_paciente = :idPaciente,
                                                    id_diagnostico = :idDiagnostico, data_entrada = :dataEntrada
                                                    WHERE historico.id = :idHistorico");
            $sql->bindValue(":idHistorico", $idHistorico);
            $sql->bindValue(":idHospital", $idHospital);
            $sql->bindValue(":idPaciente", $idPaciente);
            $sql->bindValue(":idDiagnostico", $idDiagnostico);
            $sql->bindValue(":dataEntrada", $dataEntrada);
            $sql->execute();
            return true;
        }

        public function alterarHistoricoDataSaida($idHistorico, $motivo, $dataSaida, $idPaciente){
            $sql = $this->pdo->prepare("UPDATE historico SET data_saida = :dataSaida, motivoalta = :motivoAlta 
                                                  WHERE id = :idHistorico");
            $sql->bindValue(":idHistorico", $idHistorico);
            $sql->bindValue(":dataSaida", $dataSaida);
            $sql->bindValue(":motivoAlta", $motivo);
            $sql->execute();

            //Método para definir a coluna vida como 2, no id do paciente, para tornar um óbito.
            //Se o paciente morreu, não poderá mais lançar histórico para ele.
            if($motivo == 2){
                $sql = $this->pdo->prepare("UPDATE paciente SET paciente.vida = :motivo WHERE 
                                                      paciente.id = :idPaciente");
                $sql->bindValue(":motivo", $motivo);
                $sql->bindValue(":idPaciente", $idPaciente);
                $sql->execute();
                return true;
            }elseif($motivo == 1){
                $sql = $this->pdo->prepare("UPDATE paciente SET paciente.vida = :motivo WHERE 
                                                      paciente.id = :idPaciente");
                $sql->bindValue(":motivo", $motivo);
                $sql->bindValue(":idPaciente", $idPaciente);
                $sql->execute();
                return true;
            }
        }

        public function countHistoricoAll(){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM historico");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function excluirHistorico($idHistorico){
            $sql = $this->pdo->prepare("SELECT * FROM historico WHERE id = :id");
            $sql->bindValue(":id", $idHistorico);
            $sql->execute();

            if($sql->rowCount() > 0){
                $sql = $this->pdo->prepare("DELETE FROM historico WHERE id = :id");
                $sql->bindValue(":id", $idHistorico);
                $sql->execute();
            }
            return true;
        }

        public function getDataObitoPaciente($idPaciente){
            $sql = $this->pdo->prepare("SELECT h.data_saida FROM historico h, paciente p 
                                                  WHERE h.id_paciente = p.id AND p.id = :idPaciente AND p.vida = 2");
            $sql->bindValue(":idPaciente", $idPaciente);
            $sql->execute();
            if($sql->rowCount() > 0){
                $dataObito = $sql->fetch();
                return $dataObito;
            }
        }

        public function countHistoricosUsuario($idUsuario){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM historico h, usuario u 
                                                WHERE h.id_hospital = u.id_hospital
                                                AND u.id = :idUsuario");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countHistoricoComLike($busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total,
                                                  p.nome
                                                  FROM historico h, paciente p WHERE p.id = h.id_paciente AND p.nome LIKE '%".$busca."%'");
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function countHistoricoUsuarioLike($idUsuario, $busca){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total,
                                                  p.nome
                                                  FROM historico h, paciente p, usuario u WHERE h.id_hospital = u.id_hospital
                                                  AND p.nome LIKE '%".$busca."%' AND h.id_paciente = p.id
                                                  AND u.id = :idUsuario");
            $sql->bindValue("idUsuario", $idUsuario);
            $sql->execute();
            return $total = $sql->fetch();
        }

        public function getDiagnosticosPositivos(){
            $sql = $this->pdo->query("SELECT COUNT(*) as total FROM historico WHERE id_diagnostico = 1");
            if($sql->rowCount() > 0){
                return $total = $sql->fetch();
            }
        }

        public function countHistoricoPositivos($idHospital){
            $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM historico WHERE id_diagnostico = 1 AND id_hospital = :id");
            $sql->bindValue(":id", $idHospital);
            $sql->execute();
            if($sql->rowCount() > 0){
                return $total = $sql->fetch();
            }
        }

        public function countHistoricoEmAberto($idPaciente){
            $sql = $this->pdo->prepare("SELECT COUNT(*) AS total FROM historico WHERE id_paciente = :id AND data_saida is null");
            $sql->bindValue(":id", $idPaciente);
            $sql->execute();
            if($sql->rowCount() > 0){
                return $total = $sql->fetch();
            }
        }

    }

?>
