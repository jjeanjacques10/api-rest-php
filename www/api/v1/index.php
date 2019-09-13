<?php
if ($_GET["debug"]) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';
require_once 'classes/Profissao.php';

class Rest
{

    public static function open($requisicao)
    {
        $url = explode('/', $requisicao['args']);
        $classe = "profissao";
        $arg = [];
        if ($url[0] == $classe || $url[0] == $classe.'/') {
            if (isset($_GET['CBO2002'])) {
                $metodo = "show";
                $cbo2002 = preg_replace('/[^0-9]/', '', $_GET['CBO2002']);

                if (!empty($cbo2002)) {
                    $arg =  [$cbo2002];
                } else {
                    http_response_code(400);
                    return json_encode(array('error' => 1, 'code' => 'invalid_request', 'description' => "Formato inválido de CBO2002", 'data' => ""));
                }
            } else {
                if (empty($_GET['nome'])) {
                    $metodo = "rank";
                } else {
                    $metodo = "search";
                    $arg = [$_GET['nome']];
                }
            }
        } else {
            http_response_code(422);
            return json_encode(array('error' => 1, 'code' => 'invalid_request', 'description' => "URL inválida", 'data' => ""));
        }

        try {
            $retorno = call_user_func_array(array(new $classe, $metodo), $arg);

            return json_encode(array('error' => 0, 'code' => 'model_found', 'description' => "Dados encontrados", 'data' => $retorno));
        } catch (Exception $e) {
            return json_encode(array('error' => 1, 'code' => 'invalid_request', 'description' => "", 'data' => $e->getMessage()));
        }
    }
}

echo Rest::open($_REQUEST);
