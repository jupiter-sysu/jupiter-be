<?php 
// resonse function
function var_json($code, $enmsg, $cnmsg) {
    $out['code'] = $code;
    $out['enmsg'] = $enmsg;
    $out['cnmsg'] = $cnmsg;
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}
$data_body = array();

// fetch post data
$mysqltime = date ("Y-m-d H:i:s", time());
$post_body = file_get_contents('php://input');
$post_body_json = json_decode($post_body);

$user_id = $post_body_json->user_id;
$review_id = $post_body_json->review_id;
$comment_text = $post_body_json->comment_text;

// connect to db
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');


$res = mysql_query("INSERT INTO review_comment_info_temp (review_comment_id, experience_review_text, experience_review_id, 
    user_id, create_at) VALUES (NULL, '" . $comment_text . "', '" . $review_id . "', '". $user_id . "', '" . $mysqltime . "')");

if ($res == true) {
    var_json("200", "ok", "评论成功");
} else {
    var_json("200", "failed", "评论失败");
}

?> 

