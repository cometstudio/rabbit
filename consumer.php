<?php

namespace App; //задаем найм спейс

require_once 'vendor/autoload.php'; // используем composer

use App\Classes\Rabbit; // указваем путь до файла с классами

try { // задаем блок для обработки ошибо
    $Rabbit = new Rabbit(); // оздаем экземпляр класса

    $Rabbit->consume(); // обращаемся к методу класса consumer

    $Channel=$Rabbit->channel(); // создаем пременную Channel и обращаемся к классу rabbit и методу класса channel

    while (count($Channel->callbacks))
    {

        $Channel->wait();
    }

    $Rabbit->finish();

}
catch (\Exception $e) {
    echo 'Error at '.$e->getLine().' in '.$e->getFile(). ' with ' . ($e->getMessage() ? $e->getMessage() : ' no message') . PHP_EOL;
}