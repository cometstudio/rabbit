<?php

namespace App;

require_once 'vendor/autoload.php';

use App\Classes\Rabbit;

try {
    $Rabbit = new Rabbit();


    $Rabbit->publish('Hello');

    $Rabbit->finish();

}catch (\Exception $e) {
    echo 'Error at '.$e->getLine().' in '.$e->getFile(). ' with ' . ($e->getMessage() ? $e->getMessage() : ' no message') . PHP_EOL;
}