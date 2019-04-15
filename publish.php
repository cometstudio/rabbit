<?php

namespace App;

require_once 'vendor/autoload.php';

use App\Classes\Rabbit;

try {
    $RandomMessage = mt_rand('qwerty',30);
    $Rabbit = new Rabbit();


    $Rabbit->publish($RandomMessage);

    $Rabbit->finish();

}catch (\Exception $e) {
    echo 'Error at '.$e->getLine().' in '.$e->getFile(). ' with ' . ($e->getMessage() ? $e->getMessage() : ' no message') . PHP_EOL;
}