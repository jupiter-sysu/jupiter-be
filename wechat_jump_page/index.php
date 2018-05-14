<?php 
// 1.解析URL
$return_json_str = "";
$code = null;
$state = null;
if(is_array($_GET) && count($_GET)>0) {
    if(isset($_GET["code"])) {
        $code = $_GET["code"];
    }
    if(isset($_GET["state"])) {
        $state = $_GET["state"];
    }
}
// 授权
if ($code != null) {
    // 2. 根据code向微信发送请求
    $ch = curl_init();
    $timeout = 5;
    $wechat_uri = 'http://api.weixin.qq.com/sns/oauth2/access_token?';
    $appid = 'APPID';
    $secret = 'SECRET';
    $grant_type = 'authorization_code';
    $wechat_uri_qs = $wechat_uri . 'appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=' . $grant_type;
    curl_setopt ($ch, CURLOPT_URL, $wechat_uri_qs);  
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
    $file_contents = curl_exec($ch);
    curl_close($ch); 
/*
    // test-json
    $test_json = '{ 
        "access_token":"ACCESS_TOKEN", 
        "expires_in":7200, 
        "refresh_token":"REFRESH_TOKEN",
        "openid":"1234567890123456789012345678", 
        "scope":"SCOPE",
        "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
        }';
*/
    // 3. 解析JSON，获取用户的openid

    $decoded_obj = json_decode($test_json);
    $openid = $decoded_obj->{'openid'};

    // 4. 根据openid,链接数据库查询用户是否有注册

    $servername = "39.108.15.127:3306";
    $username = "reader";
    $password = "jupiter";
    $dbname = "Jupiter_db";
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    } 

    $sql = "SELECT user_id FROM wechat_user_info WHERE openid = " . $openid;
    $result = $conn->query($sql);

    // check if wechat_login user registered before
    if ($result->num_rows > 0) {
        // if yes, send user infomation for home page
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $sql = "SELECT * FROM user_info WHERE user_id = " . $user_id;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
            $authority = $row['authority'];
            $data_arr = array('uid' => $user_id, 'username' => $username, 'authority' => $authority);
            $return_arr = array('error_code' => 0);
            $return_arr['data'] = $data_arr;
            $return_json_str = json_encode($return_arr);
            echo $return_json_str;
        }
    } else {
        $authority = 1;
        $data_arr = array('openid' => $openid, 'authority' => $authority);
        $return_arr = array('error_code' => 0);
        $return_arr['data'] = $data_arr;
        $return_json_str = json_encode($return_arr);
        echo $return_json_str;
    }

    $conn->close();
} else {
    $return_arr = array('error_code' => 411, 'messege' => "Unauthorized user.");
    $return_json_str = json_encode($return_arr);
    echo $return_json_str;
}

?> 