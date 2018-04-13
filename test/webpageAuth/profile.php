<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/7
 * Time: 15:39
 *
 *
 * 第一步：用户同意授权，获取code
 * https://www.easywechat.com/docs/master/zh-CN/official-account/oauth
 */

require '../../vendor/autoload.php';

use EasyWeChat\Factory;

$config = [
    // ...
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
    // ..
];

$app = Factory::officialAccount($config);
$oauth = $app->oauth;

// 未登录
if (empty($_SESSION['wechat_user'])) {

    $_SESSION['target_url'] = 'user/profile';

//    return $oauth->redirect();
    // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
     $oauth->redirect()->send();
}

// 已经登录过
$user = $_SESSION['wechat_user'];


var_dump($user);