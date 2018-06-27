<?php
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
function obj2Arr ($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj; 
    foreach ($_arr AS $k => $v) {
            $val = (is_object($v) ? obj2Arr($v) : $v);
            $arr[$k] = $val;
    }
    return $arr;
}
function get_user($ch,$apikey){
    curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/user/get.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $apikey)));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    checkErr($result,$error);
    return $result;
}
function send($ch,$data){
    curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    checkErr($result,$error);
    return $result;
}
function tpl_send($ch,$data){
    curl_setopt ($ch, CURLOPT_URL,
        'https://sms.yunpian.com/v2/sms/tpl_single_send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    checkErr($result,$error);
    return $result;
}
function voice_send($ch,$data){
    curl_setopt ($ch, CURLOPT_URL, 'http://voice.yunpian.com/v2/voice/send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    checkErr($result,$error);
    return $result;
}
function notify_send($ch,$data){
    curl_setopt ($ch, CURLOPT_URL, 'https://voice.yunpian.com/v2/voice/tpl_notify.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    checkErr($result,$error);
    return $result;
}

function checkErr($result,$error) {
    if($result === false)
    {
        echo 'Curl error: ' . $error;
    }
    else
    {
        //echo '操作完成没有任何错误';
    }
}
$post_body = file_get_contents('php://input');
$result_json = json_decode($post_body);
$phone_num = empty($result_json->phone_num) ? '' : $result_json->phone_num;
header("Content-Type:text/html;charset=utf-8");
$apikey = "63d34dfb1e717fccf116411b5064adef";
$random4 = rand(1000,9999);
$text="【木星屋】欢迎使用木星旅行，您的手机验证码是". $random4 ."。本条信息无需回复" ;
$ch = curl_init();

/* 设置验证方式 */
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8',
    'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'));
/* 设置返回结果为流 */
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

/* 设置超时时间*/
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

/* 设置通信方式 */
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// 取得用户信息
$json_data = get_user($ch,$apikey);
$array = json_decode($json_data,true);

$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
$result = mysql_query("SELECT * FROM user_info_temp
    WHERE phone_num=" . "'" . $phone_num . "'");
$row = mysql_fetch_array($result);

if ($row) {
	$data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$phone_num);
	$json_data = send($ch,$data);
	$array = json_decode($json_data,true);
	$_SESSION['password_message'] = $random4;
    $_SESSION['phone_temp'] = $phone_num;	
	$cnmsg = '成功。';
    $enmsg = 'ok';
    $data2['phone_num'] = $phone_num;
    var_json(200, $data2, $enmsg, $cnmsg);
} else {
    $cnmsg = '手机号未被注册，无法修改密码。';
    $enmsg = 'unregistered';
    $data2['phone_num'] = $phone_num;
    var_json(500, $data2, $enmsg, $cnmsg);
}
?>