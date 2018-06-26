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
$phone_num = empty($result_json->phone_num) ? '' : $result_json->phone_num;
$mobile_message = empty($result_json->captcha) ? '' : $result_json->captcha;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);

if ($mobile_message == $_SESSION['phone_message']) {
    mysql_query("INSERT INTO user_info_temp (phone_num, user_name, crypted_password, authority, create_at) 
VALUES ('" . $phone_num ."', '" . $phone_num . "', '" . $_SESSION['password'] . "', " . 1 .", '" . date("Y/m/d h:i:s") ."')");
    $cnmsg = '成功';
    $enmsg = 'ok';
    mysql_close($connect);
    $_SESSION['phone_message'] = '99999';
    $data['phone_num'] = $phone_num;
    var_json(200, $data, $enmsg, $cnmsg);
} else {
    $cnmsg = '验证码不匹配';
    $enmsg = 'captcha_error';
    mysql_close($connect);
    $data['phone_num'] = $phone_num;
    var_json(500, $data, $enmsg, $cnmsg);
}