<?php
echo "sso站点";

// 设置秘钥
$uid = $_GET['uid'] ?? 0;
if( empty($uid) || $uid != 100 ){
    echo 'sso授权失败！';
    die;
}

setcookie("uid", $uid, time() + 60, "/", "jj.com");
setcookie("ticket", md5($uid . "ticket"), time() + 60, "/", "jj.com");

session_start();
$_SESSION['a'] = 1;