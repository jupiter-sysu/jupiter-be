<?php
require('/alidata/www/phpwind/Jupiter/lib/password.php');
session_start();
function var_json($code, $enmsg, $cnmsg, $data) {
    $out['code'] = $code;
    $out['enmsg'] = $enmsg;
    $out['cnmsg'] = $cnmsg;
    $out['data'] = $data;
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}
$post_body = file_get_contents('php://input');
$result_json = json_decode($post_body);
$phone = empty($result_json->phone_num) ? '' : $result_json->phone_num;
$password = empty($result_json->password) ? '' : $result_json->password;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
$result = mysql_query("SELECT * FROM user_info_temp
    WHERE phone_num=" . $phone);
$row = mysql_fetch_array($result);
if (!$row) {
    var_json(500, 'unregistered_phone', '该手机号未注册', null);
} else {
    if (password_verify($password, $row['crypted_password'])) {
        $data['user_id'] = $row['user_id'];
        $data['user_name'] = $row['user_name'];
        $data['authority'] = $row['authority'];
        var_json(200, 'ok', '成功', $data);
        $_SESSION['phone_num'] = $phone;
    } else {
        var_json(500, 'wrong_password', '密码错误', null);
    }
}
?>