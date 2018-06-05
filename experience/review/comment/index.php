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
mysql_query("INSERT INTO Persons (FirstName, LastName, Age) VALUES ('Glenn', 'Quagmire', '33')");

?> 

