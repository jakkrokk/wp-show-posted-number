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

		$query = "SELECT substring(post_date,1,10) as d,count(ID) as c FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status IN ('publish','future') GROUP BY d ORDER BY ID;";
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


	public function getPostWordLen(){
		global $wpdb;
		$results = [];

		$query = "SELECT substring(post_date,1,7) as byMonth,post_content as cont FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status IN ('publish','future') ORDER BY ID;";
		$rows = $wpdb->get_results($query);

		//Create results
		$results = self::countPostData($rows);
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
	 * Create text count data
	 */
	private function countPostData($rows) {
		$results = ['byMonth'=>[],'raw'=>[],'status'=>[],'graph'=>[]];
		foreach($rows as $k=>$row) {
			$c = self::countTexts($row->cont);
			$results['byMonth'][$row->byMonth][] = $c;
			$results['raw'][] = $c;
		}
		$results['status']['max'] = max($results['raw']);
		$results['status']['min'] = min($results['raw']);
		$results['status']['avg'] = round(array_sum($results['raw']) / count($results['raw']),0);

		$i = 0;
		foreach ($results['byMonth'] as $k=>$v) {
			$results['ticks']['byMonth'][] = "[{$i},'{$k}']";
			$max = max($v);
			$min = min($v);
			$avg = round(array_sum($v) / count($v),0);
			$results['graph']['maxByMonth'][] = "[{$i},'{$max}','{$k}']";
			$results['graph']['minByMonth'][] = "[{$i},'{$min}','{$k}']";
			$results['graph']['avgByMonth'][] = "[{$i},'{$avg}','{$k}']";
			++$i;
		}

		$avg4 = self::adjust($results['status']['max']);
		$avg4 = floor($avg4 / 4);
		$results['graph']['ratio_base'] = ['avg'=>$avg4 * 2,'short'=>$avg4,'long'=>$avg4 * 3];
		$results['graph']['ratio'] = ['shorter'=>0,'short'=>0,'long'=>0,'longer'=>0];
		foreach ($results['raw'] as $v) {
			switch ((int)$v) {
				case $v > $results['status']['avg'] * 1.5:
					++$results['graph']['ratio']['longer'];
					break;
				case $v > $results['status']['avg']:
					++$results['graph']['ratio']['long'];
					break;
				case $v < $results['status']['avg'] * 0.5:
					++$results['graph']['ratio']['shorter'];
					break;
				case $v < $results['status']['avg']:
					++$results['graph']['ratio']['short'];
					break;
			}
		}
		return $results;
	}


	/**
	 * Count post text
	 */
	private function countTexts($text) {
		$text = strip_tags(mb_convert_encoding($text,'UTF-8','auto'));
		$text = preg_replace("/(\t|\r\n|\r|\n)/ius",'',$text);
		return mb_strlen($text,'UTF-8');
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


	/**
	 * Adjust number
	 */
	private function adjust($int) {
		$s = (string)$int;
		$f = $s[0];
		return str_pad($f,strlen($s),0,STR_PAD_RIGHT);
	}


}
