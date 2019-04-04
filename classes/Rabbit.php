<?php

namespace App\Classes;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Rabbit
 * @property $connection
 * @property AMQPChannel $channel
 */
class Rabbit
{
    private $connection;
    private $channel;

    /**
     * Rabbit constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * @throws \Exception
     */
    protected function connect()
    {
        $Environment = Environment::getInstance();

        $config = $Environment->config();

        $this->connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $this->channel = $this->connection->channel();
    }

    /**
     * @param string $message
     * @param string $queue
     * @return $this
     * @throws \Exception
     */
    public function publish($message, $queue = 'test')
    {
        if(empty($queue)) throw new \Exception('Queue name is not defined');

        $this->channel->queue_declare($queue, false, false, false, false);

        $msg = new AMQPMessage($message);

        for ($i=0;$i<10;$i++) {
            $this->channel->basic_publish($msg, '', $queue);
        }

        return $this;
    }

    public function finish()
    {
        $this->channel->close();
        $this->connection->close();
    }
}