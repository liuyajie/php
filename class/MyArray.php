<?php

class MyArray
{
    /**
     * 二维数组排序
     * @param $arr
     * @param $order ['id'=>'asc','name'=>'desc','age'=>'asc']
     * @return mixed
     */
    static public function multi_array_sort(&$arr, $order)
    {
        $i      = 0;
        $params = [];
        foreach ($order as $key => $sort) {
            foreach ($arr as $value) {
                $params[$i][] = $value[$key];
            }
            if (strtoupper($sort) == 'ASC') {
                $params[++$i] = SORT_ASC;
            } else {
                $params[++$i] = SORT_DESC;
            }
            ++$i;
        }
        $params[] = &$arr;
        return call_user_func_array('array_multisort', $params);
    }
}