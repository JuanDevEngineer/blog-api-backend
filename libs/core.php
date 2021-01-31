<?php

class Core
{

    public function __construct()
    {
        header("Access-Control-Allow-Origin: * ");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->init();
    }

    public function init()
    {
        $url =$this->getUrl();

        $controller  = 'controllers/'.$url[0].'Controller.php';

        // controlador a llamar
        if(file_exists($controller)) {

            require_once $controller;
            $app = new $url[0]();
            // echo "entrando al archivo";

            // metodo a cargar
            if(isset($url[1])) {

                // validamos que exista el metodo dentro de la clase controlador
                if (method_exists($app, $url[1])) {

                    // luego validamos los parametros
                    if(isset($url[2])) {
                        $nparam = sizeof($url) - 2;
                        $param = [];

                        for ($i = 0; $i < $nparam; $i++) {
                            array_push($param, $url[$i + 2]);
                        }
                        $app->{$url[1]}($param);

                    
                    } else {
                        $app->{$url[1]}();
                    }

                } else {
                    echo json_encode(array(
                        'success' => 404,
                        'msg' => 'error en el metodo de envio',
                    ));
                }
            } else {
                $app = new $url[0]();
            }

        } else {
            echo json_encode(array(
                'success' => 404,
                'msg' => 'error en el controlador de envio',
            ));
        }
    }

    public function getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
    }
}
