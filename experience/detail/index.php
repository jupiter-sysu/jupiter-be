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

// fetch experience id
$post_body = file_get_contents('php://input');
$post_body_json = json_decode($post_body);
$experience_id = $post_body_json->experience_id;

// connect to db
$connect = mysql_connect("39.108.15.127", "root", "Jupiter");
if (!$connect) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("Jupiter_db", $connect);
mysql_query('set names utf8');
$res = mysql_query("SELECT * FROM experience_info_temp WHERE experience_id=".$experience_id);
$row = mysql_fetch_assoc($res);
if (!empty($row)) {
    $data['experience_title'] = $row['experience_title'];
    $data['experience_brief_description'] = $row['experience_brief_description'];
    $data['experience_introduction'] = $row['experience_introduction'];
    /*$data['experience_feature1'];
    $data['experience_feature2'];
    $data['experience_feature3'];*/
    $user_id = $row['user_id'];
    $data['recommend_reason'] = $row['recommend_reason'];
    $data['stress_information'] = $row['stress_information'];
    $data['cover_img'] = $row['cover_img'];
    $data['create_at'] = $row['create_at'];
    $data['tags'] = [$row['experience_feature1'], $row['experience_feature2'], $row['experience_feature3']];
    $res1 = mysql_query("SELECT * FROM user_info_temp WHERE user_id=".$user_id);
    $row1 = mysql_fetch_assoc($res1);
    if (!empty($row1)) {
        $data['experience_author'] = $row1['user_name'];
    }
    

/*
    
    $data['author_profile_img'] = $row['author_profile_img'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
    $data['experience_author'] = $row['experience_author'];
  */  
}
echo(json_encode($data));
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
        "cover_img": "url",
        "experience_title": "卡帕多奇亚·星球地貌",
        "experience_brief_decription": "如果你想去月球，不妨先来这里看看",
        "experience_author": "一粒尘埃",
        "author_profile_img": "url",
        "tags": [
            "土耳其",
            "奇异",
            "壮观",
            "浪漫"
        ],
        "recommend_reason": "在这幽美的山谷两边，到处的是大块的石头，倘若你仔细看在石头，会发现上面都是密密麻麻大小不一的鸽子洞，实际上这是一些特大号的圆锥形岩层。松软的岩石酷似锥形的尖塔，尖塔顶端被大自然赋予了一块更加松软的玄武岩“帽子”。",
        "experience_introduction": "卡帕多奇亚是世界至壮观的“风化区”，触目所及尽是被“吹残”后的天然石雕。卡帕多奇亚奇石林，此地奇特之天然奇景，举世闻名，其大约于三百 万年前，由于火山爆发，熔岩及火山灰覆盖该地，后经长期风化侵蚀，成为现在特殊地形。千姿百态的石头，各种稀奇古怪的 造型，使人感叹是否来到了外星球。美国的科幻大片《星球大战》曾在此取景。由于风景独特，联合国现已将该区列入“世界遗产”的名册内。",
        "experience_introduction_imgs": [
            "url"
        ],
        "stress_information": "最佳旅行时间：4-5月、9-10月•  穿衣指南：全年注意防晒，除了夏天，其他三季要注意早晚的保暖。•  货币：土耳其通用货币是里拉。常用的纸币面值有5、10、20、50、100和200里拉。门票：成人200里拉，儿童100里拉",
        "nearby_experience": [
            {
                "experience_id": "000000",
                "feature": "奇异",
                "card_img": "url",
                "experience_title": "卡帕多奇亚：星球地貌",
                "experience_brief_discription": "如果你想去月球，不妨先来这里看看"
            },
            {
                "experience_id": "000001",
                "feature": "奇异",
                "card_img": "url",
                "experience_title": "卡帕多奇亚：星球地貌",
                "experience_brief_discription": "如果你想去月球，不妨先来这里看看"
            },
            {
                "experience_id": "000002",
                "feature": "奇异",
                "card_img": "url",
                "experience_title": "卡帕多奇亚：星球地貌",
                "experience_brief_discription": "如果你想去月球，不妨先来这里看看"
            },
            {
                "experience_id": "000003",
                "feature": "奇异",
                "card_img": "url",
                "experience_title": "卡帕多奇亚：星球地貌",
                "experience_brief_discription": "如果你想去月球，不妨先来这里看看"
            }
        ]
    }
}*/

?> 

