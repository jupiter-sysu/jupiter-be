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
$city_id = empty($result_json->city_id) ? '' : $result_json->city_id;
$experience_title = empty($result_json->experience_title) ? '' : $result_json->experience_title;
$experience_brief_description = empty($result_json->experience_brief_description) ? '' : $result_json->experience_brief_description;
$experience_introduction = empty($result_json->experience_introduction) ? '' : $result_json->experience_introduction;
$recommend_reason = empty($result_json->recommend_reason) ? '' : $result_json->recommend_reason;
$stress_information = empty($result_json->stress_information) ? '' : $result_json->stress_information;
$cover_img = empty($result_json->cover_img) ? '' : $result_json->cover_img;
$card_img = empty($result_json->card_img) ? '' : $result_json->card_img;
$experience_feature1 = empty($result_json->experience_feature1) ? '' : $result_json->experience_feature1;
$experience_feature2 = empty($result_json->experience_feature2) ? '' : $result_json->experience_feature2;
$experience_feature3 = empty($result_json->experience_feature3) ? '' : $result_json->experience_feature3;
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$data = null;
if ($experience_id != 0) {
    $str = "city_id=".$city_id.", experience_title='".$experience_title."', experience_brief_description='".$experience_brief_description."', experience_introduction='".$experience_introduction."', recommend_reason='".$recommend_reason."', stress_information='".$stress_information."', cover_img='".$cover_img."', card_img='".$card_img."', experience_feature1='".$experience_feature1."', experience_feature2='".$experience_feature2."', experience_feature3='".$experience_feature1;
    mysql_query("UPDATE experience_info_temp SET " . $str . "' WHERE experience_id=". $experience_id);
} else {
    $res = mysql_query("SELECT * FROM feature3_image_info_temp WHERE feature3_image_name='".$experience_feature3."'");
    $row = mysql_fetch_assoc($res);
    $feature3_image_id = $row['feature3_image_id'];
    $str1 = "(city_id, experience_title, experience_brief_description, experience_introduction, recommend_reason, stress_information, cover_img, card_img, experience_feature1, experience_feature2, experience_feature3, user_id, create_at, feature3_image_id, like_num)";
    $str2 = $city_id."', '".$experience_title."', '".$experience_brief_description."', '".$experience_introduction."', '".$recommend_reason."', '".$stress_information."', '".$cover_img."', '".$card_img."', '".$experience_feature1."', '".$experience_feature2."', '".$experience_feature3."', '62', '".date("Y/m/d h:i:s")."', '".$feature3_image_id."', '0'";
    //$str = "INSERT INTO experience_info_temp ".$str1." VALUES ('".$str2."')";
    mysql_query("INSERT INTO experience_info_temp ".$str1." VALUES ('".$str2.")");
}
mysql_close($connect);
var_json(200, 'ok', '成功', $data);
?>