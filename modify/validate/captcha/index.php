<?php 
header('Content-Type: text/html; charset=gb2312'); 
session_start();
function var_json($code, $data, $enmsg, $cnmsg) {
    $out['code'] = $code ?: 0;
    $out['data'] = $data ?: '';
    $out['enmsg'] = $enmsg ?: '';
    $out['cnmsg'] = $cnmsg ?: '';
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}
$post_body = file_get_contents('php://input');
$result_json = json_decode($post_body);
$captcha = empty($result_json->captcha) ? '' : $result_json->captcha;
$message_error = '';
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);

if ($captcha == $_SESSION['password_message']) {
	$cnmsg = '成功';
    $enmsg = 'ok';
	mysql_close($connect);
	$_SESSION['password_message'] = '99999';
    $_SESSION['verify_message'] = true;
    $data['phone_num'] = $_SESSION['phone_temp'];
    var_json(200, $data, $enmsg, $cnmsg);
} else {
    $cnmsg = '验证码不匹配';
    $enmsg = 'captcha_error';
    mysql_close($connect);
    $data['phone_num'] = $_SESSION['phone_temp'];
    var_json(500, $data, $enmsg, $cnmsg);
}
?>