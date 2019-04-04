<?php

namespace App\Classes;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Rabbit
 * @property $connection
 * @property AMQPChannel $channel
 * @property string $queue
 */
class Rabbit
{
    private $connection;
    private $channel;
    private $queue = 'test';

    /**
     * Rabbit constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * @param null|AMQPStreamConnection $value
     * @return null|AMQPStreamConnection
     * @throws \Exception
     */
    public function connection($value = null)
    {
        if(!is_null($value) && !$value instanceof AMQPStreamConnection) throw new \Exception('Wrong connection');

        return is_null($value) ? $this->connection : $this->connection = $value;
    }

    /**
     * @param null|AMQPChannel $value
     * @return null|AMQPChannel
     * @throws \Exception
     */
    public function channel($value = null)
    {
        if(!is_null($value) && !$value instanceof AMQPChannel) throw new \Exception('Wrong channel');

        return is_null($value) ? $this->channel : $this->channel = $value;
    }

    /**
     * @param null|string $name
     * @return null|string
     */
    public function queue($name = null)
    {
        return is_null($name) ? $this->queue : $this->queue = $name;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function connect()
    {
        $Environment = Environment::getInstance();

        $config = $Environment->config();

        $this->connection(new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']));
        $this->channel($this->connection->channel());

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     * @throws \Exception
     */
    public function publish(string $message)
    {

        if(!$queue = $this->queue()) throw new \Exception('Queue name is not defined');

        $this->channel->queue_declare($queue, false, false, false, false);

        $msg = new AMQPMessage($message);

        $this->channel->basic_publish($msg, '', $queue);

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function consume()
    {
        if(!$queue = $this->queue()) throw new \Exception('Queue name is not defined');

        $callback = function ($msg) {
            echo 'Received ' . $msg->body . PHP_EOL;
        };

        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        return $this;
    }

    public function finish()
    {
        $this->channel->close();
        $this->connection->close();
    }
}