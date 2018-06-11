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

// fetch post data
$mysqltime = date ("Y-m-d H:i:s", time());
$post_body = file_get_contents('php://input');
$post_body_json = json_decode($post_body);
$review_id = $post_body_json->review_id;
$page_num = $post_body_json->page_num;

// connect to db
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');

$res = mysql_query("SELECT * FROM experience_review_info_temp WHERE experience_review_id=" . $review_id);
$row = $res ? mysql_fetch_assoc($res) : null;
if ($row != null) {
    $user_id = $row['user_id'];
    $res1 = mysql_query("SELECT * FROM user_info_temp WHERE user_id=" . $user_id);
    $row1 = $res1 ? mysql_fetch_assoc($res1) : null;
    $data['user_name'] = $row1['user_name'];
    $data['user_profile_img'] = $row1['profile_picture'];
    $data['review_date'] = $row['create_at'];
    for ($count = 1; ($count<=3)&&($row['feature' . $count] != null); $count++) {
        $data['review_tags'][$count-1] = $row['feature' . $count];
    }
    $data['star_rank'] = $row['star_rank'];
    $data['review_text'] = $row['experience_review_text'];
    for ($count = 1; ($count<=9)&&($row['photo' . $count] != null); $count++) {
        $data['review_imgs'][$count-1] = $row['photo' . $count];
    }
    $data['like_num'] = $row['like_num'];
    $data['comment_num'] = $row['comment_num'];
} else {
    var_json("200", "ok", "成功", null);
}
$res = mysql_query("SELECT * FROM review_comment_info_temp WHERE experience_review_id=" . $review_id . " LIMIT 10 OFFSET ". ($page_num * 10));
$count = 0;
while ($row = mysql_fetch_array($res)) {
    $data['comments'][$count]['comment_text'] = $row['comment_text'];
    $user_id = $row['user_id'];
    $res1 = mysql_query("SELECT * FROM user_info_temp WHERE user_id=" . $user_id);
    $row1 = $res1 ? mysql_fetch_assoc($res1) : null;
    $data['comments'][$count]['user_name'] = $row1['user_name'];
    $count++;
}

var_json("200", "ok", "成功", $data);

?> 

