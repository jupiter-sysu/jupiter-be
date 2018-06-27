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
$res = mysql_query("SELECT * FROM experience_info_temp");
$count = 0;
while ($row = mysql_fetch_assoc($res)) {
    $count++;
}
if ($count == 0) {
    $page_sum = 0;
} else {
    $page_sum = floor($count / 10) + 1;
}
$data['page_sum'] = $page_sum;
$res = mysql_query("SELECT * FROM experience_info_temp limit " . ($page-1)*10 . ", 10");
if ($res != null) {
    $count = 0;
    while ($row = mysql_fetch_assoc($res)) {
        $items[$count]['experience_id'] = $row['experience_id'];
        $result = mysql_query("SELECT * FROM city_info_temp WHERE city_id=" . $row['city_id']);
        while ($country = mysql_fetch_assoc($result)) {
            $items[$count]['country_id'] = $country['country_id'];
        }
        $items[$count]['city_id'] = $row['city_id'];
        $items[$count]['experience_title'] = $row['experience_title'];
        $items[$count]['like_num'] = $row['like_num'];
        $items[$count]['experience_feature1'] = $row['experience_feature1'];
        $items[$count]['experience_feature2'] = $row['experience_feature2'];
        $items[$count]['experience_feature3'] = $row['experience_feature3'];
        $count++;
    }
}
$data['items'] = empty($items) ? array() : $items;
var_json(200, 'ok', '成功', $data);
?>