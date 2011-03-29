<?php

namespace PrintHack\DataService;

interface DataService
{
	// Subclass method to generate the display message.
	public function getMessage($options=array());
}