Many HP printers allow you to set their status LCD with whatever message you'd like. This has historically been used for evil ("ERROR: INTERNAL FIRE" and so on). This tool allows for more useful services, which can display interesting information. Or not.

Example usage:

	$ ./hp -t 192.168.1.34 -s Weather

Providing everything went alright, the LCD should now read "Outside: 66.20F" or whatever temperature it is in your area. You might wrap that in a sleeping loop and push that information out every so often. And people would love you for it, surely. Especially when you ovewrite annoying geek messages like "OUT OF PAPER" or "JAMMED". Who wants to see that negative jibberjabber?

You may also just send random stuff to it like so:

	$ ./hp -t 192.168.1.34 I AM A PRINTER

You are limited to 32 character messages. You may send more, but they would flow off the end of the display.