<?php
    namespace Classes\Diagnostico;
    //require_once 'Classes/Conexao/Conexao.php';
    use Classes\Conexao\Conexao;
    class DiagnosticoDao{

        public function __construct(){
            $conexao = new Conexao();
            $this->pdo = $conexao->conectar();
        }

        private $pdo;

        public function getDiagnosticos(){
            $array = array();
            $sql = $this->pdo->query("SELECT * FROM diagnostico_virus ORDER BY id");
            $sql->execute();

            if($sql->rowCount() > 0){
                $array = $sql->fetchAll();
            }
            return $array;
        }

    }

?>
