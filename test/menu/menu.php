<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/2/22
 * Time: 15:25
 */


require '../../vendor/autoload.php';

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
        'file' => __DIR__ . '/wechat' . date('Ymd') . '.log',
    ],
];

// 使用配置来初始化一个公众号应用实例。得到 application 对象
$app = Factory::officialAccount($config);


//列出 Menu List.
//$list = $app->menu->list();
//var_dump($list);


//添加普通菜单,一般在网站中，单独使用.
addNormalMenu($app);

//添加普通菜单
function addNormalMenu(\EasyWeChat\OfficialAccount\Application $app)
{
    $buttons = [
//        [
//            "type" => "click",
//            "name" => "今日歌曲",
//            "key" => "V1001_TODAY_MUSIC"
//        ],
        [
            "type" => "view",
            "name" => "测试1",
            "url" => "http://tianwechat.free.ngrok.cc/test/webpageAuth/man_webpageAuth.php"
        ],
        [
            "type" => "view",
            "name" => "测试2",
            "url" => "http://tianwechat.free.ngrok.cc/test/webpageAuth/simple_redirect.php"
        ],
        [
            "type" => "view",
            "name" => "测试3",
            "url" => "http://tianwechat.free.ngrok.cc/test/webpageAuth/profile.php"
        ],
//        [
//            "name"       => "菜单",
//            "sub_button" => [
//                [
//                    "type" => "view",
//                    "name" => "搜索",
//                    "url"  => "http://www.soso.com/"
//                ],
//                [
//                    "type" => "view",
//                    "name" => "视频",
//                    "url"  => "http://v.qq.com/"
//                ],
//                [
//                    "type" => "click",
//                    "name" => "赞一下我们",
//                    "key" => "V1001_GOOD"
//                ],
//            ],
//        ],
    ];
    $myMenuResponse = $app->menu->create($buttons);

//    $content = $myMenuResponse -> getBody() -> getContents();
    $app['logger']->addInfo(json_encode($myMenuResponse));
    var_dump($myMenuResponse);
}