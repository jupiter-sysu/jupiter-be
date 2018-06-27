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
$country_id = empty($result_json->country_id) ? '' : $result_json->country_id;
$city_name = empty($result_json->city_name) ? '' : $result_json->city_name;
$city_description = empty($result_json->city_description) ? '' : $result_json->city_description;
$card_img = empty($result_json->card_img) ? '' : $result_json->card_img;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
$result = mysql_query("SELECT * FROM city_info_temp WHERE city_name='" . $city_name . "'");
if ($result != null) {
    while ($row = mysql_fetch_assoc($result)) {
        $data['city_id'] = $row['city_id'];
    }
}
if ($flag == "1") {
    if ($data['city_id']== null) {
        mysql_query("INSERT INTO city_info_temp (country_id, city_name, city_description, card_img) 
    VALUES ('" . $country_id ."', '" . $city_name ."', '" . $city_description . "', '" . $card_img . "')");
        $cnmsg = '插入成功';
        $enmsg = 'ok';
        $data = null;
        mysql_close($connect);
        var_json(200, $enmsg, $cnmsg, $data);
    } else {
        $cnmsg = '城市信息已经存在';
        $enmsg = 'city_info_existed';
        $data = null;
        mysql_close($connect);
        var_json(500, $enmsg, $cnmsg, $data);
    }
} else {
    if ($data['city_id'] != null) {
        mysql_query("UPDATE city_info_temp SET city_description = '" . $city_description . "' WHERE city_name = '" . $city_name ."'");
        mysql_query("UPDATE city_info_temp SET card_img = '" . $card_img . "' WHERE city_name = '" . $city_name ."'");
        mysql_query("UPDATE city_info_temp SET country_id = '" . $country_id . "' WHERE city_name = '" . $city_name ."'");
        $cnmsg = '修改成功';
        $enmsg = 'ok';
        $data = null;
        mysql_close($connect);
        var_json(200, $enmsg, $cnmsg, $data);
    } else {
        $cnmsg = '城市信息未存在';
        $enmsg = 'city_info_not_existed';
        $data = null;
        mysql_close($connect);
        var_json(500, $enmsg, $cnmsg, $data);
    }
}

?>