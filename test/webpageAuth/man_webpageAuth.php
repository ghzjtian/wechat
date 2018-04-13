<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/7
 * Time: 09:38
 *

 *
 * 第一步: 用户同意授权，获取code
 */



require '../../vendor/autoload.php';
use GuzzleHttp\Client;

//echo 'Hello';

$appId = 'wxa6b6be2142fb561e';
$redirect_uri = 'http://tianwechat.free.ngrok.cc/test/webpageAuth/man_redirect_uri.php';
//$redirect_uri = 'https://www.baidu.com';

$scope = 'snsapi_base';//snsapi_userinfo , snsapi_base
$state = 'mystate';

//Demo : https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa6b6be2142fb561e&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE
$redirect_uri = urlencode($redirect_uri);


$uri = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appId.'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';

//重定向到指定的地址
//必须要这样做，否则的话(用 Client or curl)，会出现奇怪的错误.
header('location:'.$uri);

//$client = new Client();
// $client->get($uri);
//$response = $client->request("GET", $uri);
//var_dump($response);
//$body = $response->getBody()->getContents();

//print_r($body);


// 微信提供的获取token的函数
function httpGet($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
//     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
//     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

/**
 * http://www.blogfshare.com/php-curl-get-post.html
 *
 * @param $uri
 */
function httpGet2($uri){
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $uri);
    //设置头文件的信息作为数据流输出
//    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    print_r($data);
}