<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/7
 * Time: 15:41
 *
 *https://www.easywechat.com/docs/master/zh-CN/official-account/oauth
 * 第二步：通过code换取网页授权access_token
 * 第四步：拉取用户信息(需scope为 snsapi_userinfo)
 *
 */


require '../../vendor/autoload.php';

use EasyWeChat\Factory;


//if (isset($_GET['code'])) {
//    echo $_GET['code'];
////    return;
////    getAccessToken($_GET['code']);
//} else {
//    echo "NO CODE";
//}
//
//return;

$config = [
    'app_id' => 'wxa6b6be2142fb561e',
    'secret' => 'd6a3a4d88c2b40c5ec25a57988838d21',

    'response_type' => 'array',
    'token' => 'sdfsdf',
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . '/wechat' . date('Ymd') . '.log',
    ],

    'oauth' => [
        'scopes'   => ['snsapi_userinfo'],
        'callback' => '/test/webpageAuth/oauth_callback.php',
    ],
];

$app = Factory::officialAccount($config);
$oauth = $app->oauth;

// 获取 OAuth 授权结果用户信息
$user = $oauth->user();

//var_dump($user);

$_SESSION['wechat_user'] = $user->toArray();

$targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];

//header('location:' . $targetUrl); // 跳转到 user/profile