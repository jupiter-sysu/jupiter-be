<?php 
// resonse function
function var_json($code, $enmsg, $cnmsg, $data) {
    $out['code'] = $code;
    $out['enmsg'] = $enmsg;
    $out['cnmsg'] = $cnmsg;
    $out['data'] = $data;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}

// fetch experience id
$post_body = file_get_contents('php://input');
$post_body_json = json_decode($post_body);
$user_id = $post_body_json->user_id;
$review_id = $post_body_json->review_id;

// connect to db
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');

$res = mysql_query("SELECT * FROM review_like_info_temp WHERE user_id=" . $user_id . " AND experience_review_id=" . $review_id . " limit 1");
$row = $res ? mysql_fetch_assoc($res) : null;
if ($row != null) {
    $res = mysql_query("DELETE FROM review_like_info_temp WHERE user_id=" . $user_id . " AND experience_review_id=" . $review_id);
    echo 1;
} else {
    $res = mysql_query("INSERT INTO review_like_info_temp (user_id, experience_review_id) VALUES ('" . $user_id . "', '" . $review_id . "')");
    echo 2;
}
$res = mysql_query("SELECT COUNT(*) FROM review_like_info_temp WHERE experience_review_id=" . $review_id);
$row = $res ? mysql_fetch_assoc($res) : null;
$data['current_like_num'] = $row["COUNT(*)"];
$res1 =  mysql_query("UPDATE experience_review_info_temp SET like_num=". $row["COUNT(*)"] . " WHERE experience_review_id=" . $review_id);
if ($res1) var_json("200", "ok", "成功", $data);
?> 

