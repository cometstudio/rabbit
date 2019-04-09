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
     * @param null|AMQPChannel $value
     * @return null|AMQPChannel
     * @throws \Exception
     */

    public function channel($value=null)
    {
        if(!is_null($value)&& !$value instanceof AMQPChannel) throw new \Exception('Wrong Connection');
        return is_null($value) ? $this->channel : $this->channel = $value ;
    }
    /**
     * @param null|string $name
     * @return null|string
     */

    public function queue($name = null)
    {
        return is_null($name) ? $this->queue : $this->queue = $name ;
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



    public function consumer ()
    {
        if(!$queue = $this->queue()) throw new \Exception('QUEUE name is not diferent ');
        $callback=function($msg)
        {
     echo 'Received'.$msg->body.PHP_EOL;
        };
        $this->channel->basic_consume($queue,'',false , true, false ,$callback);
    return $this;
    }

    public function finish()
    {
        $this->channel->close();
        $this->connection->close();
    }


}