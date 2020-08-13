<?php

class MyDate
{
    /**
     * 获取指定日期所属月份的第一天,不传则默认当前日期
     * @param string $date
     * @return false|string
     */
    static public function get_first_day_of_month($date = '')
    {
        if (!$date) {
            return date('Y-m-01');
        }
        return date('Y-m-d', strtotime('first day of +0 month', strtotime($date)));
    }

    /**
     * 获取指定日期所属月份的最后一天,不传则默认当前日期
     * @param string $date
     * @return false|string
     */
    static public function get_last_day_of_month($date = '')
    {
        if (!$date) {
            return date('Y-m-t');
        }
        return date('Y-m-d', strtotime('last day of +0 month', strtotime($date)));
    }

    /**
     * 获取指定日期所属月份的上一个月的第一天,不传则默认当前日期
     * @param string $date
     * @return false|string
     */
    static public function get_first_day_of_prev_month($date = '')
    {
        $date = $date ?: date('Y-m-d');
        return date('Y-m-d', strtotime('first day of -1 month', strtotime($date)));
    }

    /**
     * 获取指定日期所属月份的上一个月的最后一天,不传则默认当前日期
     * @param string $date
     * @return false|string
     */
    static public function get_last_day_of_prev_month($date = '')
    {
        $date = $date ?: date('Y-m-d');
        return date('Y-m-d', strtotime('last day of -1 month', strtotime($date)));
    }

    /**
     * 获取指定日期所属月份的下一个月的第一天,不传则默认当前日期
     * @param string $date
     * @return false|string
     */
    static public function get_first_day_of_next_month($date = '')
    {
        $date = $date ?: date('Y-m-d');
        return date('Y-m-d', strtotime('first day of +1 month', strtotime($date)));
    }

    /**
     * 获取指定日期所属月份的下一个月的最后一天,不传则默认当前日期
     * @param string $date
     * @return false|string
     */
    static public function get_last_day_of_next_month($date = '')
    {
        $date = $date ?: date('Y-m-d');
        return date('Y-m-d', strtotime('last day of +1 month', strtotime($date)));
    }

    /**
     * 获取两个日期之差多少天
     * @param null $date1
     * @param null $date2
     * @param bool $contains
     * @return float|int
     */
    static public function get_diff_days_of_two_date($date1 = null, $date2 = null, $contains = true)
    {
        if (!$date1 || !$date2) {
            return 0;
        }
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        if ($contains) {
            return ceil(abs($time1 - $time2) / 86400);
        }
        return floor(abs($time1 - $time2) / 86400);
    }
}