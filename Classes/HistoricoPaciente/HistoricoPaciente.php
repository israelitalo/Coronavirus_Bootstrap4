<?php
    namespace Classes\HistoricoPaciente;
    class HistoricoPaciente {

        private $id;
        private $idHospital;
        private $idPaciente;
        private $idDiagnostico;
        private $dataEntrada;
        private $dataSaida;
        private $motivoAlta;

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getIdHospital()
        {
            return $this->idHospital;
        }

        public function setIdHospital($idHospital)
        {
            $this->idHospital = $idHospital;
        }

        public function getIdPaciente()
        {
            return $this->idPaciente;
        }

        public function setIdPaciente($idPaciente)
        {
            $this->idPaciente = $idPaciente;
        }

        public function getIdDiagnostico()
        {
            return $this->idDiagnostico;
        }

        public function setIdDiagnostico($idDiagnostico)
        {
            $this->idDiagnostico = $idDiagnostico;
        }

        public function getDataEntrada()
        {
            return $this->dataEntrada;
        }

        public function setDataEntrada($dataEntrada)
        {
            $this->dataEntrada = $dataEntrada;
        }

        public function getDataSaida()
        {
            return $this->dataSaida;
        }

        public function setDataSaida($dataSaida)
        {
            $this->dataSaida = $dataSaida;
        }

        public function getMotivoAlta()
        {
            return $this->motivoAlta;
        }

        public function setMotivoAlta($motivoAlta)
        {
            $this->motivoAlta = $motivoAlta;
        }

    }

?>
