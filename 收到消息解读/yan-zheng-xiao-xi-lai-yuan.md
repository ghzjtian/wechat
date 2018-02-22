# 服务器的验证

> https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421135319



## 逻辑步骤:

### 1.入口文件

### 2.做好配置的准备

### 3.调用 ServerGuard.php/serve() 方法,处理消息.

### 4.log 的记录
***
***
***

### 1.入口文件在 server.php 中.

```
<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/2/7
 * Time: 10:56
 */

//echo (time());

require './vendor/autoload.php';

use EasyWeChat\Factory;

// 一些配置
//token=sdfsdf
$config = [
    'app_id' => 'wx3cf0f39249eb0xxx',
    'secret' => 'f1c242f4f28f735d4687abb469072xxx',

    'response_type' => 'array',
    'token' => 'sdfsdf',
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . '/wechat.log',
    ],
];

//// 使用配置来初始化一个公众号应用实例。得到 application 对象
$app = Factory::officialAccount($config);

////接收 & 回复用户消息
//$app->server->push(function ($message) {
//    return "您好！欢迎使用 EasyWeChat!";
//});

//服务器的验证
$response = $app->server->serve();

// 将响应输出
$response->send(); // Laravel 里请使用：return $response;


```

***

### 2.做好配置的准备
* 1.在 ServiceContainer/__construct 中，配置好 app['config'] , providers , logger , app['request'] , app['http_client'] .
 
*** 

### 3.调用 ServerGuard.php/serve() 方法,处理消息.

* 1.输出 log ，显示收到的信息的具体内容,如: method/uri/content-type/content

* 2.验证消息的确来自微信服务器 validate() .

* 3.在 resolve()/handleRequest()/dispatch  中，通过 ServiceProvider 提供的回调方法,获取到微信服务器发送过来的 echostr 的值.

* 4.通过调用 shouldReturnRawResponse() ,如果请求的方法里面有 echostr 的值，就直接回复 .


***

### 4.log 的记录

```
[2018-02-21 11:33:07] easywechat.officialaccount.application.DEBUG: Request received: {"method":"GET","uri":"http://tianwechat.free.ngrok.cc/server.php/?echostr=11890214125319290483&nonce=2490554536&signature=d5396819087ad79bba52acc9a24d3c0f694541ed&timestamp=1519183987","content-type":null,"content":""} []
[2018-02-21 11:33:07] easywechat.officialaccount.application.DEBUG: Server response created: {"content":"11890214125319290483"} []
```


  
  
  




