# 关注/取消关注事件
>https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140454




### 1.相关的 log

### 1.相关的 log

***
***
***


### 1.相关的 log

* 1.关注/取消关注 不成功
```
[2018-02-21 14:41:13] easywechat.officialaccount.application.DEBUG: Request received: {"method":"POST","uri":"http://tianwechat.free.ngrok.cc/server.php/?nonce=983572791&openid=oxJ3K0usruknQwRJfNCAhqqGrKjY&signature=7d5faad8dcd4226e5cf269ceb8ba4c3c41e454b9&timestamp=1519195272","content-type":"xml","content":"<xml><ToUserName><![CDATA[gh_961af86169ad]]></ToUserName>\n<FromUserName><![CDATA[oxJ3K0usruknQwRJfNCAhqqGrKjY]]></FromUserName>\n<CreateTime>1519195272</CreateTime>\n<MsgType><![CDATA[event]]></MsgType>\n<Event><![CDATA[unsubscribe]]></Event>\n<EventKey><![CDATA[]]></EventKey>\n</xml>"} []
[2018-02-21 14:41:13] easywechat.officialaccount.application.DEBUG: Messages safe mode is enabled. [] []
[2018-02-21 14:41:38] easywechat.officialaccount.application.DEBUG: Request received: {"method":"POST","uri":"http://tianwechat.free.ngrok.cc/server.php/?nonce=1095020130&openid=oxJ3K0usruknQwRJfNCAhqqGrKjY&signature=ba61a3c0f305463f0c1dd094a380aa87c728e153&timestamp=1519195298","content-type":"xml","content":"<xml><ToUserName><![CDATA[gh_961af86169ad]]></ToUserName>\n<FromUserName><![CDATA[oxJ3K0usruknQwRJfNCAhqqGrKjY]]></FromUserName>\n<CreateTime>1519195298</CreateTime>\n<MsgType><![CDATA[event]]></MsgType>\n<Event><![CDATA[subscribe]]></Event>\n<EventKey><![CDATA[]]></EventKey>\n</xml>"} []
[2018-02-21 14:41:38] easywechat.officialaccount.application.DEBUG: Messages safe mode is enabled. [] []
```

* 2.关注成功

```
[2018-02-21 15:14:15] easywechat.officialaccount.application.DEBUG: Request received: {"method":"POST","uri":"http://tianwechat.free.ngrok.cc/server.php/?nonce=1173736018&openid=oxJ3K0usruknQwRJfNCAhqqGrKjY&signature=3dc20cf5a5714688fba9fc74b97981e957223c00&timestamp=1519197255","content-type":"xml","content":"<xml><ToUserName><![CDATA[gh_961af86169ad]]></ToUserName>\n<FromUserName><![CDATA[oxJ3K0usruknQwRJfNCAhqqGrKjY]]></FromUserName>\n<CreateTime>1519197255</CreateTime>\n<MsgType><![CDATA[event]]></MsgType>\n<Event><![CDATA[subscribe]]></Event>\n<EventKey><![CDATA[]]></EventKey>\n</xml>"} []
[2018-02-21 15:14:15] easywechat.officialaccount.application.DEBUG: Server response created: {"content":"<xml><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[您好！欢迎使用 EasyWeChat!]]></Content><ToUserName><![CDATA[oxJ3K0usruknQwRJfNCAhqqGrKjY]]></ToUserName><FromUserName><![CDATA[gh_961af86169ad]]></FromUserName><CreateTime>1519197255</CreateTime></xml>"} []

```

***

### 2.事件的处理
* 1.基本的配置/logger 的输出等等 跟验证服务器 的步骤差不多.
* 2.在 resolve()中的逻辑:
    * 1./handleRequest()/getMessage() 中取得请求的内容
    * 2.在 dispatch() 中调用回调的函数.
    * 3.在 buildResponse()/buildReply() 中，通过 Message.php/transformToXml() 得到返回的 Response 对象.







