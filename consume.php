<?php

namespace App;

use App\Classes\Rabbit;

require_once 'vendor/autoload.php';

try {
    $Rabbit = new Rabbit();

    $Rabbit->consume();

    $Channel = $Rabbit->channel();

    while (count($Channel->callbacks)) {
        $Channel->wait();
    }

    $Rabbit->finish();

}catch (\Exception $e) {
    echo 'Error at '.$e->getLine().' in '.$e->getFile(). ' with ' . ($e->getMessage() ? $e->getMessage() : ' no message') . PHP_EOL;
}