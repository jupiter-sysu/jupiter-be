<?php
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
$items[] = array();
$data = null;
$city = null;
unset($items);
$categories[] = array();
$res = mysql_query("SELECT * FROM city_info_temp");
$count = 0;
while ($row = mysql_fetch_assoc($res)) {
    $count++;
}
if ($count == 0) {
    $page_sum = 0;
} else {
    $page_sum = floor($count / 6) + 1;
}
$data['page_sum'] = $page_sum;
if ($page == 1) {
    $data['cover_img'] = "https://img-blog.csdn.net/20180521200153700";
    $discovery['title'] = "发现体验";
    $discovery['subtitle'] = "这次旅行来点新鲜的";
    $result = mysql_query("SELECT * FROM experience_info_temp limit 4");
    $i = 0;
    while ($experience = mysql_fetch_assoc($result)) {
        $items[$i]['experience_id'] = $experience['experience_id'];
        $items[$i]['experience_feature3'] = $experience['experience_feature3'];
        $items[$i]['card_img'] = $experience['card_img'];
        $items[$i]['experience_title'] = $experience['experience_title'];
        $items[$i]['experience_brief_description'] = $experience['experience_brief_description'];
        $i++;
    }
    $discovery['items'] = empty($items) ? array() : $items;
    $data['discovery'] = empty($discovery) ? array() : $discovery;
    //$discovery['items'] = $items;
    //$data['discovery'] = $discovery;
}
$res = mysql_query("SELECT * FROM city_info_temp limit " . ($page-1)*6 . ", 6");
$count = 0;
if ($res != null) {
    while ($row = mysql_fetch_assoc($res)) {
        $city[$count]['city_name'] = $row['city_name'];
        $city[$count]['city_id'] = $row['city_id'];
        $city[$count]['city_img'] = $row['city_img'];
        $results = mysql_query("SELECT * FROM experience_info_temp WHERE city_id=" . $row['city_id'] . " limit 4");
        //$items[] = array();
        unset($items);
        if ($results != null) {
            $i = 0;
            while ($experience = mysql_fetch_assoc($results)) {
                $items[$i]['experience_id'] = $experience['experience_id'];
                $items[$i]['experience_feature3'] = $experience['experience_feature3'];
                $items[$i]['card_img'] = $experience['card_img'];
                $items[$i]['experience_title'] = $experience['experience_title'];
                $items[$i]['experience_brief_description'] = $experience['experience_brief_description'];
                $i++;
            }
        }
        $result = mysql_query("SELECT distinct feature3_image_id FROM experience_info_temp where city_id = " . $row['city_id'] . " limit 6");
        //$categories[] = array();
        unset($categories);
        if ($result != null) {
            $i = 0;
            while ($feature3 = mysql_fetch_assoc($result)) {
                $feature3_result = mysql_query("SELECT * FROM feature3_image_info_temp WHERE feature3_image_id=" . $feature3['feature3_image_id']);
                $feature3_image = mysql_fetch_assoc($feature3_result);
                $categories[$i]['feature3_image_name'] = $feature3_image['feature3_image_name'];
                $categories[$i]['card_img'] = $feature3_image['card_img'];
                $i++;
            }
        }
        $city[$count]['items'] = empty($items) ? array() : $items;
        $city[$count]['categories'] = empty($categories) ? array() : $categories;
        /*if ($items != null) {
            $city[$count]['items'] = $items;
        } else {
            $city[$count]['items'] = null;
        }
        if ($categories != null) {
            $city[$count]['categories'] = $categories;
        } else {
            $city[$count]['categories'] = null;
        }*/
        $count++;
    }
}
if ($city != null) {
    $data['city'] = $city;
}
var_json(200, 'ok', '成功', $data);
?>