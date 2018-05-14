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
$password = empty($result_json->password) ? '' : $result_json->password;
$message_error = '';
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);

if ($_SESSION['verify_message'] == true) {
	mysql_query("UPDATE user_info_temp SET crypted_password = '" . $password . "' WHERE phone_num = '" . $_SESSION['phone_temp'] ."'");
	$message_error = '';
	mysql_close($connect);
	$_SESSION['verify_message'] = false;
    var_json(200, $_SESSION['phone_temp'], $message_error);
} else {
    $message_error = 'do not repeat changing your password';
    mysql_close($connect);
    var_json(401, $_SESSION['phone_temp'], $message_error);
}
?>