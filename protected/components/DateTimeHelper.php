<?php

class DateTimeHelper
{
	public static function timeElapse($from, $to = null)
	{
		$from = new DateTime($from);
		if(is_null($to))
			$to = new DateTime(date('Y-m-d H:i:s'));
		$diff = $from->diff($to);

		if($diff->m > 0)
		$display = $diff->m . ' month';
		elseif($diff->days > 0)
		$display = $diff->days . ' days';
		elseif($diff->h > 0)
		$display = $diff->h . ' hours';
		elseif($diff->i > 0)
		$display = $diff->i . ' minutes';
		
		if($from > $to)
			$display = 'in ' . $display;
		else
			$display = $display . ' ago';
		
		return $display;
	}
	
	public static function timeDiff($from, $to = null)
	{
		$from = new DateTime($from);
		if(is_null($to))
			$to = new DateTime(date('Y-m-d H:i:s'));
		$diff = $from->diff($to);

		$delay['d'] = $diff->format('%a');
		$delay['h'] = $diff->format('%h');
		
		if($from < $to){
			$diff = -1*$diff;
			$delay['d'] = -1*$delay['d'];
			$delay['h'] = -1*$delay['h'];
		}
			
		
		return $delay;
	}
}