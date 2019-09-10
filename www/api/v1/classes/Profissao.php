<?php 

    class Profissao {
        protected $con;

        public function __construct()
        {
            $this->con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        }

        public function show(){
            $sql = "SELECT * FROM automacao ORDER BY `Views` DESC;";
            $sql = $this->con->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception("Nenhuma profissao encontrada!", 1);
            }

            return $resultados;
        }

        public function search($profissao){
            $sql = 'SELECT * FROM automacao WHERE Ocupacao LIKE "%'.$profissao.'%" ORDER BY `Views` DESC;';
            $sql = $this->con->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception("Nenhuma profissao encontrada!", 1);
            }

            return $resultados;
        }

        public function newView($cbo2002){
            $sql = 'SELECT * FROM automacao WHERE CBO2002 = '.$cbo2002.';';
            $sql = $this->con->prepare($sql);
            $sql->execute();

            $resultados = array();
            
            while($row = $sql->fetch(PDO::FETCH_ORI_FIRST)){
                $resultados[] = $row;
            }
            
            if(!$resultados){
                throw new Exception("Nenhuma profissao encontrada!", 1);
            }
            $view = $resultados[0]["Views"] + 1;
            $sql = 'UPDATE `wp_it_trends`.`automacao` SET `Views`= "'.$view.'" WHERE `CBO2002` = "'.$cbo2002.'";';
            $sql = $this->con->prepare($sql);
            $sql->execute();
            
            return $resultados;
        }

        
    }