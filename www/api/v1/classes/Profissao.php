<?php

class Profissao
{
    protected $con;

    public function __construct()
    {
        $this->con = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    }

    public function rank()
    {
        $sql = "SELECT  `Ocupacao`, `Probabilidade`, `CBO2002`, `Views` FROM `ca_automacao` ORDER BY `Views` DESC LIMIT 50;";
        $sql = $this->con->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            http_response_code(204);
            throw new Exception("Nenhuma profissao encontrada!", 1);
        }
        http_response_code(200);
        return $resultados;
    }

    public function search($profissao)
    {
        $sql = 'SELECT  `Ocupacao`, `Probabilidade`, `Views`, `CBO2002` FROM `ca_automacao` WHERE Ocupacao LIKE "%' . $profissao . '%" ORDER BY `Ocupacao` ASC;';
        $sql = $this->con->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            http_response_code(204);
            throw new Exception("Nenhuma profissao encontrada!", 1);
        }
        http_response_code(200);
        return $resultados;
    }

    public function show($cbo2002)
    {
        $sql = 'SELECT * FROM `ca_automacao` WHERE CBO2002 = ' . $cbo2002 . ';';
        $sql = $this->con->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ORI_FIRST)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            throw new Exception("Nenhuma profissao encontrada!", 1);
        }

        $this->newView($cbo2002, $resultados);

        http_response_code(200);
        return $resultados;
    }

    private function newView($cbo2002, $resultados){
        //Verificando se o Cookie existe
        if ($this->verificarCookie($cbo2002)) {
            return false;
            //Verificando se o  IP existe
        } else if ($this->verificarIp($cbo2002)) {
            return false;
        }

        $view = $resultados[0]["Views"] + 1;
        $sql = 'UPDATE `ca_automacao` SET `Views`= "' . $view . '" WHERE `CBO2002` = "' . $cbo2002 . '";';
        $sql = $this->con->prepare($sql);
        $sql->execute();
    }

    private function verificarIp($cbo2002)
    {
        $retorno = false;
        $ip = $this->get_ip();

        $sql = 'SELECT `ip` FROM `ca_automacao_ip` WHERE `ip` IN ("' . $ip . '") AND `data` BETWEEN SUBDATE(CURDATE(), INTERVAL 3 DAY) AND CURDATE() AND JSON_CONTAINS(`ocupacoesview`, \'["' . $cbo2002 . '"]\');';
        $sql = $this->con->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            if (isset($_COOKIE['verificacao'])) {
                $cookie = unserialize($_COOKIE['verificacao']);
            } else {
                $cookie = [];
            }

            $array = array_merge($cookie, ["cbo2002", $cbo2002]);

            $sql = 'REPLACE INTO `ca_automacao_ip` (`ip`, `data`, `ocupacoesview`) VALUES (:ip, CURDATE( ), CONCAT(:ar));';
            $sql = $this->con->prepare($sql);

            $sql->execute(array('ip' => $ip, 'ar' => json_encode($array)));
            setcookie("verificacao", serialize($array), 0);
        } else {
            $retorno = true;
        }

        return $retorno;
    }

    private function get_ip()
    {
        $headers = $_SERVER;

        //Get the forwarded IP if it exists.
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

    private function verificarCookie($cbo2002)
    {
        if (isset($_COOKIE['verificacao'])) {
            if (in_array($cbo2002, unserialize($_COOKIE['verificacao']))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
