#!/usr/bin/env php -q
<?php

require_once __DIR__.'/lib/PrintHack.php';
require_once __DIR__.'/config.php';

$console = new PrintHack\Console;
$console->runService();
