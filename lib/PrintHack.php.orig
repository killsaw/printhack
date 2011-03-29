<?php

namespace PrintHack;

use PrintHack\DataService;

require_once __DIR__.'/PrintHack/Printer.php';
require_once __DIR__.'/PrintHack/DataService/DataService.php';
require_once __DIR__.'/PrintHack/DataService/Sales.php';
require_once __DIR__.'/PrintHack/DataService/Weather.php';
require_once __DIR__.'/PrintHack/Console.php';

class PrintHack
{
	protected $printer;
	protected $service;
	
	public function __construct($ip=null)
	{
		if (!is_null($ip)) {
			$this->setPrinterAddress($ip);
		}
	}
	
	public function setPrinterAddress($ip)
	{
		$printer = new Printer($ip);
		$this->printer = $printer;
	}
	
	public function setPrinter(Printer $printer)
	{
		$this->printer = $printer;
	}
	
	public function setService($service)
	{
		$impl = class_implements($service);
		if (!in_array('PrintHack\DataService\DataService', $impl)) {
			if (is_string($service)) {
				$class_name = "PrintHack\\DataService\\$service";
				$this->loadClass($class_name);
				$service = new $class_name;
			} else {
				throw new \Exception(
					"Unrecognized DataService input: ".
					print_r($service, true)
				);
			}
		}
		
		$this->service = $service;
	}
	
	public function updatePrinter()
	{
		$this->printer->open();
		$this->printer->setDisplayMessage(
			$this->service->getMessage()
		);
		$this->printer->close();
	}
	
	protected function loadClass($class_name, $exception=false)
	{
		if (class_exists($class_name)) {
			return true;
		}
		$class_path = sprintf("%s/%s.php", 
						__DIR__,
						str_replace('\\', '/', $class_name)
					  );
		
		if (file_exists($class_path)) {
			require_once $class_path;
		}
		
		if (!class_exists($class_name) && $exception) {
			throw new \Exception("Class '$class_name' does not exist.");
		}
	}
}
