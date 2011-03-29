<?php

namespace PrintHack;

class Printer
{
	protected $host;
	protected $port;
	protected $sock;
	protected $escapeCommands = true;
	protected $connected;

	const DEFAULT_PORT = 9100;
	const DEFAULT_TIMEOUT = 10;
	const MAX_DISPLAY_LENGTH = 32;
	
	public function __construct($host=null, $port=self::DEFAULT_PORT)
	{
		$this->host = $host;
		$this->port = $port;
		
		$this->connected = false;
	}
	
	// -------------------------------------------------
	// Printer protocol methods
	//
	
	public function getDisplayMessage()
	{
		$this->sendCommand("INFO STATUS");
		
		// Printer reply ends with the following char.
		$termination_char = chr(12);
		
		// Read command result.
		$reply_buffer = $this->readUntil($termination_char);
		
		// Parse command result.
		if ($lines = parse_ini_string($reply_buffer)) {
			if (isset($lines['DISPLAY'])) {
				return $lines['DISPLAY'];
			}
		}
		return false;
	}
	
	public function setDisplayMessage($message)
	{
		if (strlen($message) > self::MAX_DISPLAY_LENGTH) {
			throw new \Exception(
				sprintf(
					"Message is too long (%d chars). Must be %d characters or less.",
					strlen($message), self::MAX_DISPLAY_LENGTH
				)
			);
		}
		return $this->sendCommand(
					sprintf('RDYMSG DISPLAY="%s"', $message)
			   );
		
		// Never seems to return display message. Odd.
		/*
		if ($this->getDisplayMessage() == $message) {
			return true;
		} else {
			return false;
		}
		*/
	}
	
	public function sendCommand($command)
	{
		if ($this->escapeCommands) {
			$cmd = "\033%-12345X@PJL {$command}\r\n\033%-12345X\r\n";
		} else {
			$cmd = "@PJL {$command}\r\n";
		}
		return $this->write($cmd);
	}
	
	// -------------------------------------------------
	// Low-level network methods
	//
	public function open($host=null, $port=self::DEFAULT_PORT, $timeout=self::DEFAULT_TIMEOUT)
	{
		if ($host === null && $this->host !== null) {
			$host = $this->host;
		} 
		if ($host === null) {
			throw new \Exception("Printer address must be set.");
		}
		
		// Reset socket handle.
		if (is_resource($this->sock)) {
			fclose($this->sock);
		}
		
		// Create new connection.
		$this->host = $host;
		$this->port = $port;
		$this->sock = @fsockopen($host, $port, $errno, $errstr, $timeout);
		
		if (!$this->sock) {
			throw new \Exception("Failed to connect to printer: {$errstr}", $errno);			
		}
	}

	public function close()
	{
		if (is_resource($this->sock)) {
			fclose($this->sock);
		}
	}
		
	protected function write($message)
	{
		return fwrite($this->sock, $message);
	}
		
	public function read($size)
	{
		if (feof($this->sock)) {
			return false;
		} else {
			return fread($this->sock, $size);
		}
	}
		
	protected function readUntil($termination_char)
	{
		$reply_buffer = '';
		while ($char = $this->read(1)) {
			if ($char == $termination_char) {
				break;
			}
			$reply_buffer .= $char;
		}
		return $reply_buffer;
	}

	public function __destruct()
	{
		return $this->close();
	}
}
