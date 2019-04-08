<?php
/**
 * Created by PhpStorm.
 * User: shurilla
 * Date: 4/8/19
 * Time: 5:37 PM
 */
namespace App;

require_once 'vendor/autoload.php';

use App\Classes\Rabbit;

try {
    $Rabbit = new Rabbit();

    $Rabbit->consumer();
    $Channel=$Rabbit->channel();
    while (count($Channel->callbacks)){
        $Channel->wait();
    }

    $Rabbit->finish();

}catch (\Exception $e) {
    echo 'Error at '.$e->getLine().' in '.$e->getFile(). ' with ' . ($e->getMessage() ? $e->getMessage() : ' no message') . PHP_EOL;
}