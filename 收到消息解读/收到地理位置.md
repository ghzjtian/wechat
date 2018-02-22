# 收到地理位置


### 1.相关的 log

```
[2018-02-21 17:55:22] easywechat.officialaccount.application.DEBUG: Request received: {"method":"POST","uri":"http://tianwechat.free.ngrok.cc/server.php/?nonce=1420959590&openid=oxJ3K0usruknQwRJfNCAhqqGrKjY&signature=42761f374a9d6fc7ac9bb6e990c388adbeae7ea9&timestamp=1519206911","content-type":"xml","content":"<xml><ToUserName><![CDATA[gh_961af86169ad]]></ToUserName>\n<FromUserName><![CDATA[oxJ3K0usruknQwRJfNCAhqqGrKjY]]></FromUserName>\n<CreateTime>1519206911</CreateTime>\n<MsgType><![CDATA[location]]></MsgType>\n<Location_X>23.096354</Location_X>\n<Location_Y>113.398819</Location_Y>\n<Scale>16</Scale>\n<Label><![CDATA[广州悠季瑜伽学院M创工厂校区(海珠区)]]></Label>\n<MsgId>6524943999021860939</MsgId>\n</xml>"} []
[2018-02-21 17:55:22] easywechat.officialaccount.application.DEBUG: Server response created: {"content":"<xml><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[收到坐标消息]]></Content><ToUserName><![CDATA[oxJ3K0usruknQwRJfNCAhqqGrKjY]]></ToUserName><FromUserName><![CDATA[gh_961af86169ad]]></FromUserName><CreateTime>1519206922</CreateTime></xml>"} []

```