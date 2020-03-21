<?php
        class Conexao {

        private $pdo;

        private $HOST = 'localhost';
        private $USER = 'root';
        private $PASS = '';
        private $DBNAME = 'corona_virus';

        public function conectar(){
            try{
                $options = array(
                    PDO::ATTR_PERSISTENT => true,
                    /*PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8; SET CHARACTER SET UTF8;
                                                SET character_set_connection=UTF8;
                                                SET character_set_client=UTF8;',*/
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );

                $this->pdo = new PDO('mysql:host='.$this->HOST.';dbname='.$this->DBNAME.';', $this->USER, $this->PASS, $options);

                return $this->pdo;
            }catch (PDOException $ex){
                exit("Erro: ".$ex->getMessage());
            }
        }

        public function closeConection()
        {
            echo "Conexão encerrada!";
            die();
        }

    }
?>
