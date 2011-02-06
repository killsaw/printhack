<?php

namespace PrinterHack;

abstract class Printer
{
	protected $_args;
	protected $_escapeCommands;

	public function __construct($args)
	{
		$this->_args = $args;
		
		// Some HP printers do not like escape codes. Some do.
		$this->_escapeCommands = isset($args['escape'])?:true;
		
		$this->open($args);
	}

	public function __destruct()
	{
		return $this->close();
	}
	
	abstract public function open(array $args);
	abstract public function close();
	abstract public function read($size);
	abstract public function write($message);
		
	public function readUntil($termination_char)
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
		$this->sendCommand(sprintf('RDYMSG DISPLAY="%s"', $message));
		
		if ($this->getDisplayMessage() == $message) {
			return true;
		} else {
			return false;
		}
	}
	
	public function sendCommand($command)
	{
		if ($this->_escapeCommands) {
			$cmd = "\033%-12345X@PJL {$command}\r\n\033%-12345X\r\n";
		} else {
			$cmd = "@PJL {$command}\r\n";
		}
		return $this->write($cmd);
	}
	
	public static function fromIP($ip)
	{
		if (is_null($ip) || strtolower($ip) == 'null') {
			return new Printer_Null(array('host'=>null));
		} else {
			return new Printer_Network(array('host'=>$ip));
		}
	}
}
