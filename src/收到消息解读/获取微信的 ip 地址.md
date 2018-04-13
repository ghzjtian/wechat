# 获取微信的ip地址

### 1.相关的 log

* 1.error

```
[2018-02-22 09:14:36] easywechat.officialaccount.application.INFO: >>>>>>>> GET /getValidIps?access_token=7_HIpoecmhIPBL-KTnIbjx4AXhzJSB995WKG4q4Q7pG0uqvhRPhzJ6qAIBqP-hU3gILICZUuNSlCs8p_TyuKoHU5RUmJNmuezGl_bexoQmMOYMeQGm1_kgSMl3nZEQBZdAIAFQO HTTP/1.1 Host: api.weixin.qq.com User-Agent: GuzzleHttp/6.2.1 curl/7.52.1 PHP/7.1.1   <<<<<<<< HTTP/1.1 404 Not Found Connection: keep-alive Date: Thu, 22-Feb-2018 01:14:39 GMT Content-Length: 0   -------- NULL [] []

```


***

### 2.获取的逻辑

* 1.在  Base\ServiceProvider::class 中构造了 Application 的 Client 的属性,在构造的过程中，如果 access_token 没有指明，就取得 app['access_token'] .

* 2.在 Client/getValidIps() 中，











