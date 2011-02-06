<?php

namespace PrinterHack;

abstract class Service
{
	protected $options = array();
	
	const DEFAULT_SERVICE = 'Hardset';
	
	public static function run()
	{
		try {
			$opts = new \Zend_Console_Getopt(array(
				't|target=s' => 'IP address of printer (required)',
				's|service=s' => 'Service name (required)'
			));
			$opts->parse();
		} catch (\Zend_Console_Getopt_Exception $e) {
			echo $e->getUsageMessage();
			exit(1);
		}
		$target = $opts->target;
		$service = $opts->service;
		
		if (empty($target)) {
			echo $opts->getUsageMessage();
			exit(1);
		}
		
		if (empty($service)) {
			$service = self::DEFAULT_SERVICE;
		}
		
		return self::callService($service, $target, $opts);
	}
	
	public static function callService($service, $target, $opts)
	{
		try {
			// Setup printer
			$printer = Printer::fromIP($target);
		
			// Setup service
			$service_class = sprintf('PrinterHack\Service_%s', $service);		
			$service = new $service_class;
			$service->setOptions($opts);
			$output = $service->getMessage();
			
			return $printer->setDisplayMessage($output);
			
		} catch (\Exception $e) {
			fprintf(STDERR, "Error: %s\n", $e->getMessage());
			exit(1);
		}
	}
	
	public function setOptions(\Zend_Console_Getopt $options)
	{
		$this->options = $options;
	}
	
	public function getOption($name)
	{
		return $this->options->$name;
	}
	
	abstract public function getMessage();
}