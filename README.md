## Overview

Many HP printers allow you to set their status LCD to whatever message you'd like. This has historically been used for evil ("ERROR: INTERNAL FIRE" and so on). This tool turns your printer into a useful feedback device, with small amounts of data fed to it at interval by long-running processes. 

When you pass by on your way to do something more important, you can take a glance at this glowing blue LCD and know that you're seeing nearly-live data.

![Example uses](http://assets.killsaw.com/github/printhack/usecases.jpg)

## Requirements

PHP5.3+ with CLI and socket support compiled in.

## Example Usage: Manual message set 

	$ ./hardset.php -t 192.168.1.79 an example messages

And, poof! Your HP printer should now display the message you've provided.

## Example Usage: Running a service

	$ ./service.php -t 192.168.1.79 -s Weather
	
This process will keep active until killed, and update your printer with the current temperature for your area. You can skip the command-line arguments by setting defaults in the config.php file located in the base folder.

## Writing a new service
	
	namespace PrintHack;
	
	class SystemLoad implements DataService\DataService
	{
		public function getMessage($options=array())
		{
			$uptime = trim(`uptime`);
			$parts = preg_split("/load average[s]?:\s+/i", $uptime, 2);
			return $parts[1];
		}
	}
	
	// And to use it...
	$console = new Console;
	$service = new SystemLoad;
	$console->runService($service);
	
## Other ideas

- Countdown to an important milestone
- Total website hits for the day
- A single stock price, or a collection that transitions each second
- Open tickets
- Current count of online Jabber users in a company
- @hplcd twitter message displayer
- Unread email count
- Order count for the day
- Average load time for your website
- Application build status
- Randomly chosen available domain name
- Specific currency exchange rate






