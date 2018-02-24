# AccessToken的流程


### 1.注册
* 1.一进入 ServiceContainer 的构造函数中，就通过 Auth/ServiceProvider 去注册一个 $app['access_token'],的到一个 AccessToken 对象.

* 2.在 BaseClient.php 的 retryMiddleware() 中，如果在获取的过程中收到 40001 or 40002 Error.就 refresh access_token 。
* 3.EasyWeChat\Kernel\AccessToken/refresh() 的过程:
    * 1.getToken(),如果不是强制要刷新 & cache 里面有 值，就从缓存那里把值取出来.
    * 2.否则的话,requestToken(),然后保存到缓存.




