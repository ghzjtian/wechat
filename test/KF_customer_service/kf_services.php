<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/6
 * Time: 16:05
 *
 * 客服相关
 *
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140547
 * https://www.easywechat.com/docs/master/zh-CN/official-account/message-transfer
 */


require '../vendor/autoload.php';

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Transfer;


$config = [
    'app_id' => 'wxa6b6be2142fb561e',
    'secret' => 'd6a3a4d88c2b40c5ec25a57988838d21',

    'response_type' => 'array',
    'token' => 'sdfsdf',
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . '/wechat' . date('Ymd') . '.log',
    ],
];

//// 使用配置来初始化一个公众号应用实例。得到 application 对象
$app = Factory::officialAccount($config);

// 转发收到的消息给客服
$app->server->push(function($message) {
    return new Transfer();
});

$response = $app->server->serve();