<?php

namespace PrinterHack;

class Printer_Null extends Printer
{
	protected $isConnected = false;
	protected $args;
	
	public function open(array $args)
	{
		printf("Printer_Null: OPEN\n");
		$this->args = $args;
		$this->isConnected = true;
	}
	
	public function close()
	{
		printf("Printer_Null: CLOSE\n");
		$this->isConnected = false;
	}
	
	public function read($size)
	{
		printf("Printer_Null: READ: %d\n", $size);
		return false;
	}
	
	public function write($message)
	{
		printf("Printer_Null: WRITE: %s\n", $message);
		return false;
	}
}