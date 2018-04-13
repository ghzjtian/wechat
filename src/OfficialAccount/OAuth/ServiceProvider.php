<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\OfficialAccount\OAuth;

use Overtrue\Socialite\SocialiteManager as Socialite;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 * @author overtrue <i@overtrue.me>
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['oauth'] = function ($app) {
            //$socialite 为 WeChatProvider 对象.
            $socialite = (new Socialite([
                'wechat' => [
                    'client_id' => $app['config']['app_id'],
                    'client_secret' => $app['config']['secret'],
                    'redirect' => $this->prepareCallbackUrl($app),
                ],
            ], $app['request']))->driver('wechat');
            // //Overtrue\Socialite\Providers\WeChatProvider



            $scopes = (array)$app['config']->get('oauth.scopes', ['snsapi_userinfo']);

            if (!empty($scopes)) {
                $socialite->scopes($scopes);
            }

            return $socialite;
        };
    }

    /**
     * Prepare the OAuth callback url for wechat.
     * 取得 callback 的重定向的 url
     *
     * @param Container $app
     *
     * @return string
     */
    private function prepareCallbackUrl($app)
    {
        $callback = $app['config']->get('oauth.callback');
        if (0 === stripos($callback, 'http')) {
            return $callback;
        }
        $baseUrl = $app['request']->getSchemeAndHttpHost();
        //取得请求的 scheme and host,如原本为: http://localhost:63342/testPHP/symfony_http/request.php?_ijt=7h58iu5jm3g7eb66mjod9mf84d
        // 则转化后为 http://localhost:63342

        return $baseUrl . '/' . ltrim($callback, '/');
        //$callback = '/oauth_callback';
        //ltrim($callback,'/');   is return  'oauth_callback'
    }
}
