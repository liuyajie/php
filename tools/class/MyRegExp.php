<?php

class MyRegExp
{
    /**
     * 判断是不是一个合适的日期
     * @param $string
     * @param string $pattern
     * @return false|int
     */
    static public function is_date($string, $pattern = '')
    {
        if (!$pattern) {
            $pattern = '/^\d{4}[-\/]\d{1,2}[-\/]\d{1,2}$/';
        }
        return preg_match($pattern, $string);
    }

    /**
     * 获取合适的日期
     * @param $string
     * @param string $pattern
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_dates($string, $pattern = '', $is_get_all = false)
    {
        if (!$pattern) {
            $pattern = '/\d{4}[-\/]\d{1,2}[-\/]\d{1,2}/';
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }

    /**
     * 判断是不是一个合格的手机号
     * @param $string
     * @param null $pattern
     * @return false|int
     */
    static public function is_phone($string, $pattern = null)
    {
        if (!$pattern) {
            $pattern = '/^(13|14|15|18|17)\d{9}$/';
        }
        return preg_match($pattern, $string);
    }

    /**
     * 获取合适的手机号
     * @param $string
     * @param null $pattern
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_phones($string, $pattern = null, $is_get_all = false)
    {
        if (!$pattern) {
            $pattern = '/(13|14|15|18|17)\d{9}/';
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }

    /**
     * 判断是不是一个合格的邮箱
     * @param $string
     * @param null $pattern
     * @return false|int
     */
    static public function is_email($string, $pattern = null)
    {
        if (!$pattern) {
            $pattern = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
        }
        return preg_match($pattern, $string);
    }

    /**
     * 获取合格的邮箱
     * @param $string
     * @param null $pattern
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_emails($string, $pattern = null, $is_get_all = false)
    {
        if (!$pattern) {
            $pattern = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }

    /**
     * 判断是不是中国大陆的身份证号码
     * @param $string
     * @param null $pattern
     * @return false|int
     */
    static public function is_id($string, $pattern = null)
    {
        if (!$pattern) {
            $pattern = '/^(11|12|13|14|15|21|22|23|31|32|33|34|35|36|37|41|42|43|44|45|46|50|51|52|53|54|61|62|63|64|65)\d{15}[0-9|X]$/';
        }
        return preg_match($pattern, $string);
    }

    /**
     * 获取中国大陆的身份证号码
     * @param $string
     * @param null $pattern
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_ids($string, $pattern = null, $is_get_all = false)
    {
        if (!$pattern) {
            $pattern = '/(11|12|13|14|15|21|22|23|31|32|33|34|35|36|37|41|42|43|44|45|46|50|51|52|53|54|61|62|63|64|65)\d{15}[0-9|X]/';
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }

    /**
     * 判断是否是一个ip(ipv4)
     * @param $string
     * @param null $pattern
     * @return false|int
     */
    static public function is_ip($string, $pattern = null)
    {
        if (!$pattern) {
            $pattern = '/^\d{3}\.\d{3}\.\d{3}\.\d{3}$/';
        }
        return preg_match($pattern, $string);
    }

    /**
     * 获取符合条件的ipv4
     * @param $string
     * @param null $pattern
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_ips($string, $pattern = null, $is_get_all = false)
    {
        if (!$pattern) {
            $pattern = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/';
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }

    /**
     * 判断密码是否合适
     * @param $string
     * @param null $pattern
     * @return false|int
     */
    static public function is_password($string, $pattern = null)
    {
        if (!$pattern) {
            $pattern = '/^[a-zA-Z0-9]\w{5,17}$/';
        }
        return preg_match($pattern, $string);
    }

    /**
     * 获取合适的密码
     * @param $string
     * @param null $pattern
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_passwords($string, $pattern = null, $is_get_all = false)
    {
        if (!$pattern) {
            $pattern = '/[a-zA-Z0-9]\w{5,17}/';
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }

    /**
     * 判断是否是汉字,默认为utf8编码 \[\x{4e00}-\x{9fa5}\]表示一个汉字
     * @param $string
     * @param null $pattern
     * @param string $charset
     * @return false|int
     */
    static public function is_chinese_char($string, $pattern = null, $charset = 'utf8')
    {
        if (!$pattern) {
            switch ($charset) {
                case 'utf8':
                    $pattern = '/^[\x{4e00}-\x{9fa5}]+$/';
                    break;
                case 'gbk':
                    $pattern = '/^[' . chr(0xa1) . '-' . chr(0xff) . ']$/';
                    break;
            }
        }
        return preg_match($pattern, $string);
    }

    /**
     * 判断是否是汉字,默认为utf8编码 \[\x{4e00}-\x{9fa5}\]表示一个汉字
     * @param $string
     * @param null $pattern
     * @param string $charset
     * @param bool $is_get_all
     * @return mixed
     */
    static public function get_chinese_chars($string, $pattern = null, $charset = 'utf8', $is_get_all = false)
    {
        if (!$pattern) {
            switch ($charset) {
                case 'utf8':
                    $pattern = '/[\x{4e00}-\x{9fa5}]+/u';
                    break;
                case 'gbk':
                    $pattern = '/[' . chr(0xa1) . '-' . chr(0xff) . ']/';
                    break;
            }
        }
        preg_match_all($pattern, $string, $datas);
        return $is_get_all ? $datas : $datas[0];
    }
}