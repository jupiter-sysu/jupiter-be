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
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
$items = array();
$res = mysql_query("SELECT * FROM country_info_temp");
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
$res = mysql_query("SELECT * FROM country_info_temp limit " . ($page-1)*8 . ", 8");
if ($res != null) {
    $count = 0;
    while ($row = mysql_fetch_assoc($res)) {
        $items[$count]['country_id'] = $row['country_id'];
        $result = mysql_query("SELECT * FROM city_info_temp WHERE country_id=" . $row['country_id']);
        $count1 = 0;
        while ($row2 = mysql_fetch_assoc($result)) {
            $count1++;
        }
        $items[$count]['country_name'] = $row['country_name'];
        $items[$count]['card_img'] = $row['card_img'];
        $items[$count]['number_of_city'] = $count1;
        $count++;
    }
}
$data['items'] = empty($items) ? array() : $items;
var_json(200, 'ok', '成功', $data);
?>