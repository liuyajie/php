<?php

class MyString
{
    /**
     * chunk_split的中文版
     * @param $string
     * @param $length
     * @param $end
     * @return string
     */
    static public function mb_chunk_split($string, $length, $end)
    {
        $array  = [];
        $strLen = mb_strlen($string);
        while ($strLen) {
            $array[] = mb_substr($string, 0, $length, "utf-8");
            $string  = mb_substr($string, $length, $strLen, "utf-8");
            $strLen  = mb_strlen($string);
        }
        return implode($end, $array);
    }

}