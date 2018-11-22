<?php
Class Spn {
	public function getPostedNumbers(){
		global $wpdb;
		$results = [];
		$results['weekdayRawTmp'] = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0];

		//Create weekday ticks
		$label = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'];
		foreach ($label as $k=>$v) {
			$results['weekdayLabel'][] = "[{$k},'{$v}']";
		}

		$query = "SELECT substring(post_date,1,10) as d,count(ID) as c FROM {$wpdb->posts} WHERE post_status IN ('publish','future') GROUP BY d ORDER BY ID;";
		$rows = $wpdb->get_results($query);

		//Create start date and end date
		$results = self::createStartEnd($results,$rows);

		//Get raw data
		$results = self::createRawData($results,$rows);

		//Get post status
		$results = self::createPostStatus($results,$rows);

		//Create weekday raws
		foreach ($results['weekdayRawTmp'] as $k=>$v) {
			$results['weekdayRaw'][] = "[{$k},{$v},'{$label[$k]}']";
		}

		return $results;
	}


	/**
	 * Create raw data
	 */
	private function createStartEnd($results,$rows) {
		$row = array_shift($rows);
		$results['status']['first'] = $row->d;
		$row = array_pop($rows);
		$results['status']['last'] = $row->d;
		$results['status']['range'] = self::createDateRange($results['status']['first'],$results['status']['last']);
		return $results;
	}


	/**
	 * Create raw data
	 */
	private function createRawData($results,$rows) {
		$sum = 0;

		$raw = [];
		foreach($rows as $k=>$row) {
			$raw[$row->d] = $row;
		}

		//Create raw data.
		foreach($results['status']['range'] as $k=>$date) {
			if (isset($raw[$date])) {
				$row = $raw[$date];
				$results['ticks'][] = "[{$k},'{$date}']";
				$results['raws'][] = "[{$k},{$row->c},'{$row->d}']";
				$sum += (int)$row->c;
				$results['sum'][] = "[{$k},{$sum},'{$row->d}']";

				$weekday = date('w',strtotime($row->d));
				$weekday = $weekday ? $weekday : 7;
				$results['weekdayRawTmp'][$weekday] += 1;
			} else {
				$results['ticks'][] = "[{$k},'{$date}']";
				$results['raws'][] = "[{$k},0,'{$date}']";
			}
		}
		$results['sumRaw'] = $sum;
		return $results;
	}


	/**
	 * Create post status
	 */
	private function createPostStatus($results,$rows) {
		$results['status']['counts'] = $results['sumRaw'];
		$results['status']['post_range'] = count($results['status']['range']);
		$results['status']['post_day'] = count($rows);
		$results['status']['no_post_day'] = $results['status']['post_range'] - $results['status']['post_day'];
		return $results;
	}


	/**
	 * Create date range
	 */
	private function createDateRange($start,$end) {
		//reverse if start > end
		if (date($start) > date($end)) {
			list($end,$start) = [$start,$end];
		}

		$ret = [];
		$n = 0;
		$myEnd = strtotime($end);
		$myDate = strtotime("{$start} +{$n} day");
		while ($myDate <= $myEnd) {
			$ret[] = date('Y-m-d',$myDate);
			$n++;
			$myDate = strtotime("{$start} +{$n} day");
		}
		return $ret;
	}
}
