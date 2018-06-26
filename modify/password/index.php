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
function generateHash($password) {
  if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
    return crypt($password, $salt);
  }
}
$post_body = file_get_contents('php://input');
$result_json = json_decode($post_body);
$password = empty($result_json->password) ? '' : $result_json->password;
$password = generateHash($password);
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);

if ($_SESSION['verify_message'] == true) {
	mysql_query("UPDATE user_info_temp SET crypted_password = '" . $password . "' WHERE phone_num = '" . $_SESSION['phone_temp'] ."'");
	$cnmsg = '成功';
    $enmsg = 'ok';
	mysql_close($connect);
	$_SESSION['verify_message'] = false;
    $data['phone_num'] = $_SESSION['phone_temp'];
    var_json(200, $data, $enmsg, $cnmsg);
} else {
    $cnmsg = '请勿重复操作';
    $enmsg = 'no_repeat';
    mysql_close($connect);
    $data['phone_num'] = $_SESSION['phone_temp'];
    var_json(500, $data, $enmsg, $cnmsg);
}
?>