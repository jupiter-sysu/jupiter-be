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

if ($page_num == 0) {
    $res = mysql_query("SELECT * FROM experience_info_temp WHERE experience_id=".$experience_id);
    $row = mysql_fetch_assoc($res);
    if (!empty($row)) {
        $data['cover_img'] = $row['cover_img'];
        $data['experience_title'] = $row['experience_title'];
        $data['experience_brief_description'] = $row['experience_brief_description'];
        $data['experience_introduction'] = $row['experience_introduction'];
        $data['tags'] = [$row['experience_feature1'], $row['experience_feature2'], $row['experience_feature3']];
    } else {
        var_json(404, "experience does not exist", "", null);
    }
}
$res1 = mysql_query("SELECT * FROM experience_review_info_temp WHERE experience_id=".$experience_id."LIMIT 10 OFFSET".$page_num*10);
while($row=mysql_fetch_assoc($res)){
    echo json_encode($row);
}

//echo(json_encode($data));
//var_json(200, 'ok', '成功', $experience_title);
//file_get_contents("php://input"); 
//$data_json = array("cover_img" =>)
//$response_json = array("code" => 200, "enmsg" => "ok", "cnmsg" => "成功");

/*
{
    "code": 200,
    "enmsg": "ok",
    "cnmsg": "成功",
    "data": {
        "cover_img": "https://img-blog.csdn.net/20180531002326199?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
        "experience_title": "卡帕多奇亚·星球地貌",
        "experience_brief_decription": "如果你想去月球，不妨先来这里看看",
        "tags": [
            "土耳其",
            "奇异",
            "壮观",
            "浪漫"
        ],
        "reviews": [
            {
                "review_id": "000000",
                "user_name": "onefour",
                "user_profile_img": "https://img-blog.csdn.net/20180601122000725?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                "review_date": "2018-01-25",
                "star_rank": "4",
                "review_tags": [
                    "土耳其",
                    "奇异",
                    "浪漫"
                ],
                "review_text": "真心是非常神奇壮观的地方……感受自然吧！",
                "review_imgs": [
                    "https://img-blog.csdn.net/20180531002255820?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                    "https://img-blog.csdn.net/20180531002316985?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                    "https://img-blog.csdn.net/20180531002308878?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                    "https://img-blog.csdn.net/20180531002418338?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                    "https://img-blog.csdn.net/20180531002335718?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                    "https://img-blog.csdn.net/20180531002410289?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70"
                ],
                "like_num": "23",
                "comment_num": "1"
            },
            {
                "review_id": "000001",
                "user_name": "onefour",
                "user_profile_img": "https://img-blog.csdn.net/20180601122000725?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                "review_date": "2018-01-25",
                "star_rank": "4",
                "review_tags": [
                    "土耳其",
                    "奇异",
                    "浪漫"
                ],
                "review_text": "真心是非常神奇壮观的地方……感受自然吧！",
                "review_imgs": [
                    "https://img-blog.csdn.net/20180531002410289?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70"
                    
                ],
                "like_num": "23",
                "comment_num": "1"
            },
            {
                "review_id": "000002",
                "user_name": "onefour",
                "user_profile_img": "https://img-blog.csdn.net/20180601122000725?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                "review_date": "2018-01-25",
                "star_rank": "4",
                "review_tags": [
                    "土耳其",
                    "奇异",
                    "浪漫"
                ],
                "review_text": "真心是非常神奇壮观的地方……感受自然吧！",
                "review_imgs": [
                    "url1",
                    "url2"
                ],
                "like_num": "23",
                "comment_num": "1"
            },
            {
                "review_id": "000003",
                "user_name": "onefour",
                "user_profile_img": "https://img-blog.csdn.net/20180601122000725?watermark/2/text/aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3FxXzM4MTIxMzAw/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70",
                "review_date": "2018-01-25",
                "star_rank": "4",
                "review_tags": [
                    "土耳其",
                    "奇异",
                    "浪漫"
                ],
                "review_text": "真心是非常神奇壮观的地方……感受自然吧！",
                "review_imgs": [
                    "url1",
                    "url2"
                ],
                "like_num": "23",
                "comment_num": "1"
            }
        ]
    }
}
*/


?> 

