<?php

namespace PrinterHack;

class Service_Hardset extends Service
{
	const MAX_SIZE = 32;
	
	public function getMessage()
	{
		$message = trim(join(' ', $this->options->getRemainingArgs()));
		if (strlen($message) > self::MAX_SIZE) {
			throw new \Exception(
						"Message is too long (".strlen($message).
						" chars). Must be ".self::MAX_SIZE." characters or less.\n"
						);
		}
		return $message;
	}
}