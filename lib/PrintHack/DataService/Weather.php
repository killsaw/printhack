<?php

namespace PrintHack\DataService;

class Weather implements DataService
{
	public function getMessage($options=array())
	{
		$geoip =json_decode(
			file_get_contents('http://freegeoip.net/json/'),
			TRUE
		);
		
		$weather=json_decode(
			file_get_contents(
				'http://ws.geonames.org/findNearByWeatherJSON?'.
				http_build_query(
					array(
						'username'=>$geoip['ip'],
						'lat'=>$geoip['latitude'],
						'lng'=>$geoip['longitude']
					)
				)
			),
			TRUE
		);
		
		$temp_c = $weather['weatherObservation']['temperature'];
		
		$degree_type = $this->getOption('d');
		if (is_null($degree_type) || strtolower($degree_type) == 'f') {
			$deg_str = sprintf('%.2fF', ($temp_c*1.8)+32);
		} else {
			$deg_str = sprintf('%.2fC', $temp_c);
		}
		return 'Outside: '.$deg_str;
	}
}