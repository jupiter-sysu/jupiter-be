<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
$city_id = empty($result_json->city_id) ? '' : $result_json->city_id;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
$result = mysql_query("SELECT * FROM city_info_temp WHERE city_id=" . $city_id);
while ($row2 = mysql_fetch_assoc($result)) {
    $data['city_id'] = $row2['city_id'];
    $data['city_name'] = $row2['city_name'];
    $data['city_description'] = $row2['city_description'];
    $data['card_img'] = $row2['card_img'];
}
var_json(200, 'ok', '成功', $data);
?>