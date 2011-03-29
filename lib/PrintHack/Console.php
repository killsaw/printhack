<?php

namespace PrintHack;

if (!class_exists('\Zend_Loader_Autoloader')) {
	require_once __DIR__.'/../vendor/Zend/Loader/Autoloader.php';
	$loader = \Zend_Loader_Autoloader::getInstance();
	$loader->registerNamespace('Zend');
}

class Console
{
	protected $printer;
	protected $service;
	protected $options;
	protected $ph;
	
	public function __construct()
	{
		try {
			$opts = new \Zend_Console_Getopt(array(
				't|target' => 'IP address of printer (optional)',
				's|service' => 'Service name (optional)'
			));
			$opts->parse();
		} catch (\Zend_Console_Getopt_Exception $e) {
			echo $e->getUsageMessage();
			exit(1);
		}
		
		$this->printer = $opts->target;
		$this->service = $opts->service;
		$this->options = $opts;
		
		if (empty($this->printer) &&  defined('DEFAULT_PRINTER_IP')) {
			$this->printer = DEFAULT_PRINTER_IP;
		}
		if (empty($this->service) && defined('DEFAULT_SERVICE')) {
			$this->service = DEFAULT_SERVICE;
		}
	}
	
	public function commandlineSet()
	{
		$message = join(' ', $this->options->getRemainingArgs());

		try {
			if (empty($message)) {
				throw new \Exception("Message must be set.");
			}
			$printer = new Printer;
			$printer->open($this->printer);
			$printer->setDisplayMessage($message);
			unset($printer);
			return true;
			
		} catch (\Exception $e) {
			fprintf(STDERR, "Error: %s\n", $e->getMessage());
			exit(1);
		}
	}

	public function runService($service=null, $update_interval=60)
	{
		if ($service === null) {
			$service = $this->service;
		}
		$hack = new PrintHack;
		$hack->setService($service);
		$hack->setPrinterAddress($this->printer);
		
		while(true) {
			printf("[%s] Running printer update.\n", date('c'));
			$hack->updatePrinter();
			sleep($update_interval);
		}
	}
}