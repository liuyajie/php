<?php

$start = new DateTime();
//$interval = new DateInterval('P1W');

$interval = DateInterval::createFromDateString('-1 day');

$period = new DatePeriod($start,$interval,3);

foreach ($period as $value) {
    echo  $value->format('Y-m-d H:i:s').'<br/>';
}