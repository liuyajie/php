<?php

if (!isset($_COOKIE['ticket'])) {
    echo 'a站点没有授权登录！3秒后自动去授权中心！';
//    echo '<script>setTimeout(function(){window.location="http://sso.jj.com/days/20210616/sso.php?uid=100"},3000)</script>';
    die;
}

$uid = $_COOKIE['uid'];
if ($_COOKIE['ticket'] != md5($uid . "ticket")) {
    echo 'a站点授权失败！';
    die;
}
// 初步条件

// 二次校验
$chche->get('uid'.$uid);

echo 'a站点授权成功';
