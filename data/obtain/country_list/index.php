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
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
//$country_list[] = array();
$city_list = array();
$res = mysql_query("SELECT * FROM country_info_temp");
if ($res != null) {
    $count = 0;
    while ($row = mysql_fetch_assoc($res)) {
        $country_list[$count]['country_id'] = $row['country_id'];
        $country_list[$count]['country_name'] = $row['country_name'];
        $result = mysql_query("SELECT * FROM city_info_temp WHERE country_id=" . $row['country_id']);
        if ($result != null) {
            unset($city_list);
            $i = 0;
            while ($cities = mysql_fetch_assoc($result)) {
                $city_list[$i]['city_id'] = $cities['city_id'];
                $city_list[$i]['city_name'] = $cities['city_name'];
                $i++;
            }
            $country_list[$count]['city_num'] = $i;
        }
        $country_list[$count]['city_list'] = empty($city_list) ? array() : $city_list;
        $count++;
    }
}
$data['country_list'] = empty($country_list) ? array() : $country_list;
var_json(200, 'ok', '成功', $data);
?>