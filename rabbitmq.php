<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * RabbitMQ Sample Code
 *
 * Manage rabbitmq messages 
 *
 * @package		RabbitMQ Sample Code
 * @subpackage	        Libraries
 * @category	        Libraries
 * @author		Juan Mescher
 * info 		https://www.rabbitmq.com/tutorials/tutorial-two-php.html
 */

require_once __DIR__ . '/rabbitmq/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ 
{
	private $conn = FALSE;
	private $channel = FALSE;
	private $queue = FALSE;

	public function RabbitMQ() 
	{
		$this->conn = new AMQPConnection(conf('rabbit.server_ip'), conf('rabbit.server_port'), conf('rabbit.server_user'), conf('rabbit.server_pass'));
		if ($this->conn) 
		{
			$this->channel = $this->conn->channel();
		}		
	}

	public function set_queue($name) 
	{
		$this->channel->queue_declare($name, FALSE, TRUE, FALSE, FALSE);
		$this->queue = $name;
		return TRUE;
	}

	public function get_queue() 
	{
		return $this->queue;
	}

	public function send_message($msg)
	{
		$msg = new AMQPMessage($msg, array('delivery_mode' => 2));
		$this->channel->basic_publish($msg, '', $this->get_queue());		
	}

	public function close() {
		$this->channel->close();
		$this->conn->close();		
	}
}
