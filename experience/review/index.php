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

$data_body = array();

// fetch experience id & page_num
$post_body = file_get_contents('php://input');
$post_body_json = json_decode($post_body);
$experience_id = $post_body_json->experience_id;
$page_num = $post_body_json->page_num;

// connect to db
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');

// fetch head info
if ($page_num == 0) {
    $res = mysql_query("SELECT * FROM experience_info_temp WHERE experience_id=".$experience_id);
    $row = mysql_fetch_assoc($res);
    if (!empty($row)) {
        $data['cover_img'] = $row['cover_img'];
        $data['experience_title'] = $row['experience_title'];
        $data['experience_brief_description'] = $row['experience_brief_description'];
        $data['tags'] = [$row['experience_feature1'], $row['experience_feature2'], $row['experience_feature3']];
    } else {
        var_json(200, "Experience does not exist", "体验不存在！", null);
    }
}
// fetch reviews
$res = mysql_query("SELECT * FROM experience_review_info_temp WHERE experience_id=".$experience_id." LIMIT 10 OFFSET ".$page_num*10);
$count = 0;
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
    // get review info
    $data['reviews'][$count]['review_id'] = $row['experience_review_id'];
    $data['reviews'][$count]['review_date'] = $row['create_at'];
    $data['reviews'][$count]['star_rank'] = $row['star_rank'];
    $data['reviews'][$count]['review_text'] = $row['experience_review_text'];
    for ($count1 = 1; ($count1 <= 3) && ($row["feature" . $count1] != null); $count1++) {
        $data['reviews'][$count]['review_tags'][$count1 - 1] = $row["feature" . $count1];
    }
    for ($count1 = 1; ($count1 <= 9) && ($row["photo" . $count1] != null); $count1++) {
        $data['reviews'][$count]['review_imgs'][$count1 - 1] = $row["photo" . $count1];
    }
    // get user info
    $res1 = mysql_query("SELECT * FROM user_info_temp WHERE user_id = " . $row['user_id']);
    $row1 = mysql_fetch_assoc($res1);
    $data['reviews'][$count]['user_name'] = $row1['user_name'];
    $data['reviews'][$count]['user_profile_img'] = $row1['profile_picture'];
    $data['reviews'][$count]['like_num'] = $row['like_num'];
    $data['reviews'][$count]['comment_num'] = $row['comment_num'];
    $count++;
}
// response
var_json(200, "ok", "成功", $data);
?> 

