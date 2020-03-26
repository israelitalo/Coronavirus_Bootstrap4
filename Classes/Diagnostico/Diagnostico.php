<?php
    namespace Classes\Diagnostico;
    class Diagnostico{

        private $id;
        private $status;

        public function getId()
        {
            return $this->id;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function setStatus($status)
        {
            $this->status = $status;
        }

    }
?>
