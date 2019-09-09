<?php 

    class Pokemon {
        public function show(){
            $con = new PDO('mysql:host=********;dbname=********', '********', '********');

            $sql = "SELECT * FROM pokemon;";
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception("Nenhum Pokemon encontrado!", 1);
            }

            return $resultados;
        }

        public function search($nome){
            $con = new PDO('mysql:host=********;dbname=********', '********', '********');

            $sql = 'SELECT * FROM pokemon WHERE nome LIKE "%'.$nome.'%";';
            $sql = $con->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception("Nenhum Pokemon encontrado!", 1);
            }

            return $resultados;
        }
    }