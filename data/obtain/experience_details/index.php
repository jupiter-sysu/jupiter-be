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
$experience_id = empty($result_json->experience_id) ? '' : $result_json->experience_id;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
$res = mysql_query("SELECT * FROM experience_info_temp WHERE experience_id=" . $experience_id);
if ($res != null) {
    while ($row = mysql_fetch_assoc($res)) {
        $result = mysql_query("SELECT * FROM city_info_temp WHERE city_id=" . $row['city_id']);
        while ($country = mysql_fetch_assoc($result)) {
            $data['country_id'] = $country['country_id'];
        }
        $data['city_id'] = $row['city_id'];
        $data['experience_title'] = $row['experience_title'];
        $data['experience_brief_description'] = $row['experience_brief_description'];
        $data['experience_introduction'] = $row['experience_introduction'];
        $data['recommend_reason'] = $row['recommend_reason'];
        $data['stress_information'] = $row['stress_information'];
        $data['cover_img'] = $row['cover_img'];
        $data['card_img'] = $row['card_img'];
        $data['experience_feature1'] = $row['experience_feature1'];
        $data['experience_feature2'] = $row['experience_feature2'];
        $data['experience_feature3'] = $row['experience_feature3'];
    }
}
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