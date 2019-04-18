<?php

namespace App;

require_once 'vendor/autoload.php';

use App\Classes\Rabbit;

try {
    $Rabbit = new Rabbit();

    $RandomMessage = str_repeat('a',1024);

    $config = $Rabbit->config();
    $TimeBegin = microtime(true);

    for ($i=0;$i<$config['message']['publish'];$i++) {
        $Rabbit->publish($RandomMessage);

    }

    $TimeEnd = microtime(true);

    //$AllTime = (($TimeEnd-$TimeBegin)/$config);

//    echo 'Среднее время выполнения '.$AllTime ,PHP_EOL;

    $Rabbit->finish();

}catch (\Exception $e) {
    echo 'Error at ' . $e->getLine() . ' in ' . $e->getFile() . ' with ' . ($e->getMessage() ? $e->getMessage() : ' no message') . PHP_EOL;
}