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
$search = empty($result_json->search) ? '' : $result_json->search;
$type = empty($result_json->type) ? '' : $result_json->type;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
$country = null;
$city = null;
$experience = null;
if ($type == 1) {
    $res = mysql_query("SELECT * FROM country_info_temp");
    if ($res != null) {
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            if (strpos($row['country_name'], $search) !== false) {
                $country[$count]['country_id'] = $row['country_id'];
                $country[$count]['country_name'] = $row['country_name'];
                $count++;
            }
        }
    }
    $res = mysql_query("SELECT * FROM city_info_temp");
    if ($res != null) {
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            if (strpos($row['city_name'], $search) !== false) {
                $city[$count]['city_id'] = $row['city_id'];
                $city[$count]['city_name'] = $row['city_name'];
                $result = mysql_query("SELECT * FROM country_info_temp WHERE country_id=" . $row['country_id']);
                while ($rows = mysql_fetch_assoc($result)) {
                    $city[$count]['country_name'] = $rows['country_name'];
                }
                $count++;
            }
        }
    }
    $data['country'] = empty($country) ? array() : $country;
    $data['city'] = empty($city) ? array() : $city;
} else {
    $res = mysql_query("SELECT * FROM experience_info_temp");
    if ($res != null) {
        $count = 0;
        while ($row = mysql_fetch_assoc($res)) {
            if (strpos($row['experience_title'], $search) !== false) {
                $experience[$count]['experience_id'] = $row['experience_id'];
                $experience[$count]['experience_title'] = $row['experience_title'];
                $count++;
            }
        }
    }
    $data['experience'] = empty($experience) ? array() : $experience;
}
var_json(200, 'ok', '成功', $data);
?>