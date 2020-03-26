<?php
    namespace Classes\Pacientes;
    class Paciente{

        //Todos os métodos abaixo serão serão apagados, e serão implementados os gets e seters para cada atributo.

        private $id;
        private $idHospital;
        private $nome;
        private $cpf;
        private $sexo;
        private $dataNascimento;
        private $rua;
        private $numero;
        private $bairro;
        private $cidade;
        private $estado;
        private $cep;
        private $telefone;

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

        public function getNome()
        {
            return $this->nome;
        }

        public function setNome($nome)
        {
            $this->nome = $nome;
        }

        public function getCpf()
        {
            return $this->cpf;
        }

        public function setCpf($cpf)
        {
            $this->cpf = $cpf;
        }

        public function getSexo()
        {
            return $this->sexo;
        }

        public function setSexo($sexo)
        {
            $this->sexo = $sexo;
        }

        public function getDataNascimento()
        {
            return $this->dataNascimento;
        }

        public function setDataNascimento($dataNascimento)
        {
            $this->dataNascimento = $dataNascimento;
        }

        public function getRua()
        {
            return $this->rua;
        }

        public function setRua($rua)
        {
            $this->rua = $rua;
        }

        public function getNumero()
        {
            return $this->numero;
        }

        public function setNumero($numero)
        {
            $this->numero = $numero;
        }

        public function getBairro()
        {
            return $this->bairro;
        }

        public function setBairro($bairro)
        {
            $this->bairro = $bairro;
        }

        public function getCidade()
        {
            return $this->cidade;
        }

        public function setCidade($cidade)
        {
            $this->cidade = $cidade;
        }

        public function getEstado()
        {
            return $this->estado;
        }

        public function setEstado($estado)
        {
            $this->estado = $estado;
        }

        public function getCep()
        {
            return $this->cep;
        }

        public function setCep($cep)
        {
            $this->cep = $cep;
        }

        public function getTelefone()
        {
            return $this->telefone;
        }

        public function setTelefone($telefone)
        {
            $this->telefone = $telefone;
        }

    }

?>
