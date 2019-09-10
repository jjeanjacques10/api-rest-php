<?php 

header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';
require_once 'classes/Profissao.php';

class Rest{

    public static function open($requisicao){
        $url = explode('/', $requisicao['args']);
        
        $classe = $url[0];
        $metodo = $url[1];
        $arg[] = $url[2];

        try{
            if(class_exists($classe)){
                if(method_exists($classe, $metodo)){   
                    $retorno = call_user_func_array(array(new $classe, $metodo), $arg );

                    return json_encode(array('status' => 'success', 'result' => $retorno));
                }else{
                    return json_encode(array('status' => 'fail', 'result' => 'Metodo iniexistente!'));
                }
            }else{
                return json_encode(array('status' => 'fail', 'result' => 'Classe iniexistente!'));
            }
        }catch(Exception $e){
            return json_encode(array('status' => 'fail', 'result' => $e->getMessage()));
        }

        
    }
}

if(isset($_REQUEST)){
   echo Rest::open($_REQUEST);
}