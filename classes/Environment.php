<?php

namespace App\Classes;

/**
 * Class Environment
 * @property array $config
 */
class Environment
{
    private static $_instance = null;
    private $config = [];

    private function __clone () {}
    private function __wakeup () {}

    /**
     * @param string $pathToConfig
     * @throws \Exception
     */
    public function __construct($pathToConfig = '.env')
    {
        if(empty($pathToConfig) || !file_exists($pathToConfig)) throw new \Exception('No config');

        $this->config = parse_ini_file($pathToConfig, $process_sections = true);
    }

    /**
     * Singleton implementation
     * @return self
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function config()
    {
        return $this->config;
    }
}