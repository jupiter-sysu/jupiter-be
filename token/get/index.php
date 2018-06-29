<?php
require_once '/alidata/www/phpwind/Jupiter/php-sdk-master/php-sdk-master/autoload.php';

use \Qiniu\Auth;

$accessKey = 'XXXX';
$secretKey = 'XXXX';
$bucket = 'jupiter';

$auth = new Auth($accessKey, $secretKey);

$expires = 3600;

$policy = null;
$upToken = $auth->uploadToken($bucket, null, $expires, $policy, true);

function var_json($error_code, $data) {
	$out['code'] = $error_code ?: 0;
	$out['cnmsg'] = '成功';
	$out['enmsg'] = 'ok';
    $out['data'] = $data ?: '';
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}
$data['token'] = $upToken;
var_json(200, $data);
