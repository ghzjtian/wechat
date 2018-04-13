<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/6
 * Time: 15:32
 *
 * https://www.easywechat.com/docs/master/zh-CN/official-account/template_message
 *
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 *获取消息发送的模板
 */



require '../vendor/autoload.php';

use EasyWeChat\Factory;

// 一些配置
//token=sdfsdf
$config = [
    'app_id' => 'wxa6b6be2142fb561e',
    'secret' => 'd6a3a4d88c2b40c5ec25a57988838d21',

    'response_type' => 'array',
    'token' => 'sdfsdf',
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . '/wechat'.date('Ymd').'.log',
    ],
];

// 使用配置来初始化一个公众号应用实例。得到 application 对象
$app = Factory::officialAccount($config);


$template = $app->template_message->getPrivateTemplates();

var_dump($template);