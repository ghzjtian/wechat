<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/6
 * Time: 14:38
 *
 * https://www.easywechat.com/docs/master/zh-CN/basic-services/qrcode
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542
 *
 *
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


$result = $app->qrcode->temporary('foo', 6 * 24 * 3600);
var_dump($result);
// Array
// (
//     [ticket] => gQFD8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyTmFjVTRWU3ViUE8xR1N4ajFwMWsAAgS2uItZAwQA6QcA
//     [expire_seconds] => 518400
//     [url] => http://weixin.qq.com/q/02NacU4VSubPO1GSxj1p1k
// )

//$ticket = $result['ticket'];
//$url = $app->qrcode->url($ticket);
//
//$content = file_get_contents($url); // 得到二进制图片内容
//
//file_put_contents(__DIR__ . '/code.jpg', $content); // 写入文件