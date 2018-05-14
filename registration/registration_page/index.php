<?php 
header('Content-Type: text/html; charset=gb2312'); 
session_start();
function var_json($error_code, $phone_num, $message_error) {
	$out['error_code'] = $error_code ?: 0;
    $out['phone_num'] = $phone_num ?: '';
    $out['message_error'] = $message_error ?: '';
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}
$post_body = file_get_contents('php://input');
$result_json = json_decode($post_body);
$phone_num = empty($result_json->phone_num) ? '' : $result_json->phone_num;
$mobile_message = empty($result_json->mobile_message) ? '' : $result_json->mobile_message;
$message_error = '';
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);

if ($mobile_message == $_SESSION['phone_message']) {
	mysql_query("INSERT INTO user_info_temp (phone_num, user_name, crypted_password, authority, create_at) 
VALUES ('" . $phone_num ."', '" . $phone_num . "', '" . $_SESSION['password'] . "', " . 1 .", '" . date("Y/m/d h:i:s") ."')");
	$message_error = '';
	mysql_close($connect);
	$_SESSION['phone_message'] = '99999';
    var_json(200, $phone_num, $message_error);
} else {
    $message_error = 'mobile message does not match';
    mysql_close($connect);
    var_json(401, $phone_num, $message_error);
}
?>