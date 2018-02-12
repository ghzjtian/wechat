# 服务器的验证

>https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421135319

##验证的步骤:

* 1. Factory 通过魔术方法 __callStatic ，调用 make 方法，取得   \EasyWeChat\OfficialAccount\Application 的对象实例.

* 2.在 Application 的构造方法中，完成了一下的初始化操作:
	* 1.配置好 配置文件 $this['config'] ,如 :
	
	```
	 public function __construct(array $config = [], array $prepends = [])
    {
        parent::__construct($prepends);

        $this->registerConfig($config)//根据所给的配置信息,配置好 配置文件,$this['config']
            ->registerProviders() //把服务提供者的服务全部注册
            ->registerLogger()  //注册 monolog
            ->registerRequest() //"symfony/http-foundation"
            ->registerHttpClient(); //取得 GuzzleHttp ， 请求体 class.

        $this->id = md5(json_encode($config));
    }
	```
```
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
 protected $globalConfig = [
        // http://docs.guzzlephp.org/en/stable/request-options.html
        'http' => [
            'timeout' => 5.0,
            'base_uri' => 'https://api.weixin.qq.com/',
        ],
    ];
```

* 3.开始 执行服务端业务了

	```
	$response = $app->server->serve();
	
	```

	* 1.调用 ``` \EasyWeChat\OfficialAccount\Server\Guard            $server ```
	 服务提供者的 实例，该实例已经在  ``` Server\ServiceProvider::class ``` 中实现实例化了.

	* 2.```$response = $app->server->serve(); ```详解:

		* 1.通过 $app 的到 Guard 的实例后,执行 ```serve()``` 方法
		* 2.输出 log 信息
		* 3.通过 ```validate()``` 验证 收到的数据是否正确(用过微信服务器的验证方法.)(可以在 ```ServerGuard:$alwaysValidate``` 中开关)
		* 4.在 ```resolve()``` 中，我们将通过回调函数，调用 ```ServiceProvider -> register ``` 中 ```New Guard```实例  的 ```EchoStrHandler.handle``` 方法.得到 微信服务器发过来的 ```echostr``` 的值.
		* 5.然后再返回一个 ```Symfony\Component\HttpFoundation\Response``` 的值去相应输出操作.








