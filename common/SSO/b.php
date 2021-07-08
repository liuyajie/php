<?php

if (!isset($_COOKIE['ticket'])) {
    echo 'b站点没有授权登录！3秒后自动去授权中心！';
    echo '<script>setTimeout(function(){window.location="http://sso.jj.com/days/20210616/sso.php?uid=100"},3000)</script>';
    die;
}

$uid = $_COOKIE['uid'];
if ($_COOKIE['ticket'] != md5($uid . "ticket")) {
    echo 'b站点授权失败！';
    die;
}
echo 'b站点授权成功';
