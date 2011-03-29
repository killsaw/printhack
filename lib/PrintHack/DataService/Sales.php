<?php

namespace PrintHack\DataService;

class Sales implements DataService
{
	const DB_NAME = 'ecommerce';
	const DB_USER = 'root';
	const DB_PASS = 'root';
	const DB_HOST = 'localhost';
	
	const SUM_QUERY = "SELECT SUM(total_revenue) total FROM orders LIMIT 1";

	public function getMessage($options=array())
	{
		try {
		
			if (!($conn = mysql_connect(self::DB_HOST, self::DB_USER, self::DB_PASS))) {
				throw new Exception("Failed to connect to db: ".mysql_error());
			}
			if (!mysql_select_db(self::DB_NAME, $conn)) {
				throw new Exception("Failed to select db: ".mysql_error());
			}
			
			// Simple aggregate.
			$res = mysql_query(self::SUM_QUERY);
			$row = mysql_fetch_assoc($res);
			mysql_close($conn);
			
			return sprintf('Sales: $%s', number_format($row['total'], 0));
		
		} catch (Exception $e) {
			openlog("PHSalesService", LOG_PID | LOG_PERROR, LOG_LOCAL0);			
			syslog(LOG_ERR, "Failed to connect to db: ".mysql_error());
			closelog();
			return false;
		}
	}
}