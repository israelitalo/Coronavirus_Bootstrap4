<?php
    namespace Classes\Usuarios;
    class Usuarios{

        private $id;
        private $ativo;
        private $nome;
        private $login;
        private $email;
        private $senha;
        private $idHospital;
        private $telefone;

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }
        public function getAtivo()
        {
            return $this->ativo;
        }

        public function setAtivo($ativo)
        {
            $this->ativo = $ativo;
        }

        public function getNome()
        {
            return $this->nome;
        }

        public function setNome($nome)
        {
            $this->nome = $nome;
        }

        public function getLogin()
        {
            return $this->login;
        }

        public function setLogin($login)
        {
            $this->login = $login;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getSenha()
        {
            return $this->senha;
        }

        public function setSenha($senha)
        {
            $this->senha = $senha;
        }

        public function getIdHospital()
        {
            return $this->idHospital;
        }

        public function setIdHospital($idHospital)
        {
            $this->idHospital = $idHospital;
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
