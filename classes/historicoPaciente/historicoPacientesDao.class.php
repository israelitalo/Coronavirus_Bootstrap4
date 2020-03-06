<?php
    require_once 'classes/conexao/conexao.class.php';
    class HistoricoPacienteDao{

        private $pdo;

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
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
                                            u.nome,
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

        //Método para retornar os históricos através do termo de busca pesquisado.
        public function getHistoricoLike($busca){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            p.id as id_paciente, p.nome as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, paciente p where h.id_paciente = p.id and p.nome like '%".$busca."%';");
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function getHistoricoLikeForUserLogado($busca, $idUsuario){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            p.id as id_paciente, p.nome as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id)
                                            as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico 
                                            FROM historico h, paciente p, usuario u
                                            WHERE h.id_hospital = u.id_hospital AND h.id_paciente = p.id AND u.id = :idUsuario
                                            AND p.nome LIKE '%".$busca."%' ORDER BY h.id");
            $sql->bindValue(":idUsuario", $idUsuario);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

        public function addHistorico($idHospital, $idPaciente, $idDiagnostico, $dataEntrada){
            //global $pdo;
            $sql = $this->pdo->prepare("INSERT INTO historico SET id_hospital = :idHospital, id_paciente = :idPaciente,
                                            id_diagnostico = :idDiagnostico, data_entrada = :dataEntrada");
            $sql->bindValue(":idHospital", $idHospital);
            $sql->bindValue(":idPaciente", $idPaciente);
            $sql->bindValue(":idDiagnostico", $idDiagnostico);
            $sql->bindValue(":dataEntrada", $dataEntrada);
            $sql->execute();
            return true;
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

        public function alterarHistoricoDataSaida($idHistorico, $idDiagnostico, $motivo, $dataSaida){
            //global $pdo;
            $sql = $this->pdo->prepare("UPDATE historico SET id_diagnostico = :idDiagnostico, 
                                                data_saida = :dataSaida, motivoalta = :motivoAlta 
                                                WHERE id = :idHistorico");
            $sql->bindValue(":idHistorico", $idHistorico);
            $sql->bindValue(":idDiagnostico", $idDiagnostico);
            $sql->bindValue(":dataSaida", $dataSaida);
            $sql->bindValue(":motivoAlta", $motivo);
            $sql->execute();

            return true;

            /*if($motivo == 1){
                //Se o count for maior que 0, significa que existe uma alta pra aquele paciente e historico, sendo necessário atualizar a data
                //e não inserir um novo dado na tabela.
                $sql = $this->pdo->prepare("SELECT alta.id FROM alta WHERE alta.id_historico = :idHistorico
                                                    AND alta.id_paciente = :idPaciente AND alta.id_hospital = :idHospital
                                                    AND alta.data = :dataSaida");
                $sql->bindValue(":idHistorico", $idHistorico);
                $sql->bindValue(":idPaciente", $idPaciente);
                $sql->bindValue(":idHospital", $idHospital);
                $sql->bindValue(":dataSaida", $dataSaida);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $idAlta = $sql->fetch();
                    $sql = $this->pdo->prepare("UPDATE alta SET alta.data = :dataSaida WHERE alta.id = :idAlta");
                    $sql->bindValue(":idAlta", $idAlta);
                    $sql->bindValue(":dataSaida", $dataSaida);
                    $_SESSION['msg'] = "Data da alta alterada com sucesso.";
                    return true;
                }else{
                    $sql = $this->pdo->prepare("INSERT INTO alta SET id_historico = :idHistorico, id_paciente = :idPaciente,
                                                id_hospital = :idHospital, data = :dataSaida");
                    $sql->bindValue(":idPaciente", $idPaciente);
                    $sql->bindValue(":idHospital", $idHospital);
                    $sql->bindValue(":dataSaida", $dataSaida);
                    $sql->bindValue(":idHistorico", $idHistorico);
                    $sql->execute();
                    $_SESSION['msg'] = "Data da alta inserida com sucesso.";
                    return true;
                }
            }elseif($motivo == 2){

                $sql = $this->pdo->prepare("SELECT obito.id FROM obito WHERE obito.id_historico = :idHistorico
                                                    AND obito.id_paciente = :idPaciente AND obito.id_hospital = :idHospital
                                                    AND obito.data = :dataSaida");
                $sql->bindValue(":idHistorico", $idHistorico);
                $sql->bindValue(":idPaciente", $idPaciente);
                $sql->bindValue(":idHospital", $idHospital);
                $sql->bindValue(":dataSaida", $dataSaida);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $idObito = $sql->fetch();
                    $sql = $this->pdo->prepare("UPDATE obito SET obito.data = :dataSaida WHERE obito.id = :idObito");
                    $sql->bindValue(":idObito", $idObito);
                    $sql->bindValue(":dataSaida", $dataSaida);
                    $_SESSION['msg'] = "Data do obito alterada com sucesso.";
                    return true;
                }else{
                    $sql = $this->pdo->prepare("INSERT INTO obito SET id_historico = :idHistorico, id_paciente = :idPaciente,
                                                id_hospital = :idHospital, data = :dataSaida");
                    $sql->bindValue(":idPaciente", $idPaciente);
                    $sql->bindValue(":idHospital", $idHospital);
                    $sql->bindValue(":dataSaida", $dataSaida);
                    $sql->bindValue(":idHistorico", $idHistorico);
                    $sql->execute();
                    $_SESSION['msg'] = "Data do obito inserida com sucesso.";
                    return true;
                }
            }*/
        }

        public function getHistoricoPacienteAlta($idHistorico, $dataSaida){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            a.data AS alta,
                                            (select nome from paciente where h.id_paciente = paciente.id) as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id) as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, alta a 
                                            WHERE h.id = :idHistorico 
                                            AND a.id_historico = :idHistorico 
                                            AND data = :dataSaida");
            $sql->bindValue(":idHistorico", $idHistorico);
            $sql->bindValue(":dataSaida", $dataSaida);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetch();
            }
            return $array;
        }

        public function getHistoricoPacienteObito($idHistorico, $dataSaida){
            //global $pdo;
            $array = array();
            $sql = $this->pdo->prepare("SELECT h.id, h.id_hospital, h.id_paciente, h.id_diagnostico, h.data_entrada, h.data_saida,
                                            o.data AS obito,
                                            (select nome from paciente where h.id_paciente = paciente.id) as paciente,
                                            (select nome from hospital where h.id_hospital = hospital.id) as hospital,
                                            (select status from diagnostico_virus where h.id_diagnostico = diagnostico_virus.id) as diagnostico
                                            FROM historico h, obito o 
                                            WHERE h.id = :idHistorico 
                                            AND o.id_historico = :idHistorico 
                                            AND data = :dataSaida");
            $sql->bindValue(":idHistorico", $idHistorico);
            $sql->bindValue(":dataSaida", $dataSaida);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetch();
            }
            return $array;
        }

    }

?>
