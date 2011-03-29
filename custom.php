#!/usr/bin/env php -q
<?php

namespace PrintHack;

require_once __DIR__.'/lib/PrintHack.php';
require_once __DIR__.'/config.php';


class SystemLoad implements DataService\DataService
{
    public function getMessage($options=array())
    {
        $uptime = trim(`uptime`);
        $parts = preg_split("/load average[s]?:\s+/i", $uptime, 2);
        return $parts[1];
    }
}

namespace PrintHack;
$console = new Console;
$service = new SystemLoad;
$console->runService($service);
