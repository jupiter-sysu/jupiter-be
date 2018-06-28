<?php
header('Content-Type: text/html; charset=gb2312');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
$flag = empty($result_json->flag) ? '' : $result_json->flag;
$country_name = empty($result_json->country_name) ? '' : $result_json->country_name;
$country_description = empty($result_json->country_description) ? '' : $result_json->country_description;
$card_img = empty($result_json->card_img) ? '' : $result_json->card_img;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$result = mysql_query("SELECT * FROM country_info_temp WHERE country_name='" . $country_name . "'");
if ($result != null) {
    while ($row = mysql_fetch_assoc($result)) {
        $data['country_id'] = $row['country_id'];
        $data['country_name'] = $row['country_name'];
    }
}
if ($flag == "1") {
    if ($data['country_id']== null) {
        mysql_query("INSERT INTO country_info_temp (country_name, country_description, card_img) 
    VALUES ('" . $country_name ."', '" . $country_description . "', '" . $card_img . "')");
        $cnmsg = '插入成功';
        $enmsg = 'ok';
        $data = null;
        mysql_close($connect);
        var_json(200, $enmsg, $cnmsg, $data);
    } else {
        $cnmsg = '国家信息已经存在';
        $enmsg = 'country_info_existed';
        $data = null;
        mysql_close($connect);
        var_json(500, $enmsg, $cnmsg, $data);
    }
} else {
    if ($data['country_id'] != null) {
        mysql_query("UPDATE country_info_temp SET country_description = '" . $country_description . "' WHERE country_name = '" . $country_name ."'");
        mysql_query("UPDATE country_info_temp SET card_img = '" . $card_img . "' WHERE country_name = '" . $country_name ."'");
        $cnmsg = '修改成功';
        $enmsg = 'ok';
        $data = null;
        mysql_close($connect);
        var_json(200, $enmsg, $cnmsg, $data);
    } else {
        $cnmsg = '国家信息未存在';
        $enmsg = 'country_info_not_existed';
        $data = null;
        mysql_close($connect);
        var_json(500, $enmsg, $cnmsg, $data);
    }
}

?>