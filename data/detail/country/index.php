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
$page = empty($result_json->page) ? '' : $result_json->page;
$country_id = empty($result_json->country_id) ? '' : $result_json->country_id;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
$items = array();
$res = mysql_query("SELECT * FROM city_info_temp");
$count = 0;
while ($row = mysql_fetch_assoc($res)) {
    $count++;
}
if ($count == 0) {
    $page_sum = 0;
} else {
    $page_sum = floor($count / 8) + 1;
}
$data['page_sum'] = $page_sum;
$result = mysql_query("SELECT * FROM country_info_temp WHERE country_id=" . $country_id);
while ($row2 = mysql_fetch_assoc($result)) {
    $data['country_id'] = $row2['country_id'];
    $data['country_name'] = $row2['country_name'];
    $data['card_img'] = $row2['card_img'];
}
$res = mysql_query("SELECT * FROM city_info_temp WHERE country_id=" . $country_id . " limit " . ($page-1)*8 . ", 8");
if ($data != null) {
    $count = 0;
    while ($row = mysql_fetch_assoc($res)) {
        $items[$count]['city_id'] = $row['city_id'];
        $result = mysql_query("SELECT * FROM experience_info_temp  WHERE city_id=" . $row['city_id']);
        $count1 = 0;
        while ($row2 = mysql_fetch_assoc($result)) {
            $count1++;
        }
        $items[$count]['city_name'] = $row['city_name'];
        $items[$count]['card_img'] = $row['card_img'];
        $items[$count]['number_of_experience'] = $count1;
        $count++;
    }
}
$data['items'] = empty($items) ? array() : $items;
var_json(200, 'ok', '成功', $data);
?>