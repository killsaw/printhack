<?php

namespace PrinterHack;

class Printer_Network extends Printer
{
	protected $_host;
	protected $_port;
	protected $_sock;
	
	const DEFAULT_PORT = 9100;
	const DEFAULT_TIMEOUT = 10;
	
	public function open(array $args)
	{
		// Reset socket handle.
		if (is_resource($this->_sock)) {
			fclose($this->_sock);
		}
		$this->_sock = false;
		$this->_host = $args['host'];
		$this->_port = isset($args['port'])?$args['port']:self::DEFAULT_PORT;

		$this->_sock = @fsockopen($this->_host, $this->_port, 
							      &$errno, &$errstr, 
							      self::DEFAULT_TIMEOUT);
		
		if (!$this->_sock) {
			throw new \Exception("Failed to connect to HP printer: {$errstr}", $errno);			
		}
	}

	public function close()
	{
		if (is_resource($this->_sock)) {
			fclose($this->_sock);
		}
	}
		
	public function write($message)
	{
		return fwrite($this->_sock, $message);
	}
		
	public function read($size)
	{
		if (feof($this->_sock)) {
			return false;
		} else {
			return fread($this->_sock, $size);
		}
	}
}