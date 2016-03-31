<?php
/**
 * Created by PhpStorm.
 * User: breeze
 * Date: 16/3/31
 * Time: 上午11:28
 */
require './include/common.inc.php';
defined('IN_ISERVER') or exit('Access Denied');

$serv = new swoole_http_server("0.0.0.0", 9502);

$serv->on('Request', function($request, $response) {
    $response->header("Server", "ISERVER");

    $dir = $request->get["_d"];
    $file = $request->get["_f"];
    $method = $request->get["_m"];

    if (!empty($dir) && !empty($file) && file_exists('./module/'.$dir."/".$file .'.php')) {
        require_once ('./module/'.$dir."/".$file .'.php');
        $obj = new $file();
        if (method_exists($obj, $method)) {
            $result = $obj->$method($request->get, $request->post);
            $response->end($result);
        }else{
            $response->end('error:'.ISERVER_ROOT.'module/'.$dir."/".$file .'.php method "'.$method .'" does not exist');
        }
    }else{
        $response->end('error:'.ISERVER_ROOT.'module/'.$dir."/".$file .'.php'.' not found');
    }
});

$serv->start();
?>