<?php
/**
 * Created by PhpStorm.
 * User: tianzeng
 * Date: 2018/3/7
 * Time: 10:18
 *
 *
 * 自己打造的 网络授权
 *获取了授权后，重定向的 page.
 *
 * 第二步：通过code换取网页授权access_token
 * 第三步：刷新access_token（如果需要）
 * 第四步：拉取用户信息(需scope为 snsapi_userinfo)
 */


require '../../vendor/autoload.php';

use GuzzleHttp\Client;

if (isset($_GET['code'])) {
//    echo $_GET['code'];
//    return;
    getAccessToken($_GET['code']);
} else {
    echo "NO CODE";
}

//根据 code ，取得 AccessToken
function getAccessToken(string $code)
{


    $appId = 'wxa6b6be2142fb561e';
    $appsecret = 'd6a3a4d88c2b40c5ec25a57988838d21';


    //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appId . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';
//    print_r($url);
//    return ;

    $client = new Client();
    $response = $client->get($url);
//    $response = $client->request("GET", $url);
    $body = $response->getBody()->getContents();
//    var_dump($body);
    $body = json_decode($body,true);

//    var_dump($body);
//    return;
    getUserInfo($body['access_token'],$body['openid']);

}

//第三步：刷新access_token（如果需要
//根据refresh Token ,刷新 Token
function refreshToken(string $refreshToken){

    $appId = 'wxa6b6be2142fb561e';
    //https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN
    $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appId.'&grant_type=refresh_token&refresh_token='.$refreshToken;
    $client = new Client();
    $response = $client->get($url);
//    $response = $client->request("GET", $url);
    $body = $response->getBody()->getContents();

    print_r($body);


}

/**
 * 第四步：拉取用户信息(需scope为 snsapi_userinfo)
 */
function getUserInfo(string $accessToken,string $openId){

    //https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN

    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken.'&openid='.$openId.'&lang=zh_CN';
    $client = new Client();
    $response = $client->get($url);
//    $response = $client->request("GET", $url);
    $body = $response->getBody()->getContents();

    print_r($body);
}
