<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\Kernel;

use EasyWeChat\Kernel\Contracts\MessageInterface;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Raw as RawMessage;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Support\XML;
use EasyWeChat\Kernel\Traits\Observable;
use EasyWeChat\Kernel\Traits\ResponseCastable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ServerGuard.
 *
 * 1. url 里的 signature 只是将 token+nonce+timestamp 得到的签名，只是用于验证当前请求的，在公众号环境下一直有
 * 2. 企业号消息发送时是没有的，因为固定为完全模式，所以 url 里不会存在 signature, 只有 msg_signature 用于解密消息的
 *
 * @author overtrue <i@overtrue.me>
 */
class ServerGuard
{
    use Observable, ResponseCastable;

    /**
     * 是否需要经常验证的 flag ,默认为 false.
     * @var bool
     */
    protected $alwaysValidate = false;

    /**
     * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140543
     * 被动回复用户消息,
     * 假如服务器无法保证在五秒内处理并回复，必须做出下述回复，这样微信服务器才不会对此作任何处理，并且不会发起重试（这种情况下，可以使用客服消息接口进行异步回复），否则，将出现严重的错误提示。详见下面说明：
    1、直接回复success（推荐方式） 2、直接回复空串（指字节长度为0的空字符串，而不是XML结构体中content字段的内容为空）
     * Empty string.
     */
    const SUCCESS_EMPTY_RESPONSE = 'success';

    /**
     * @var array
     */
    const MESSAGE_TYPE_MAPPING = [
        'text' => Message::TEXT,
        'image' => Message::IMAGE,
        'voice' => Message::VOICE,
        'video' => Message::VIDEO,
        'shortvideo' => Message::SHORT_VIDEO,
        'location' => Message::LOCATION,
        'link' => Message::LINK,
        'device_event' => Message::DEVICE_EVENT,
        'device_text' => Message::DEVICE_TEXT,
        'event' => Message::EVENT,
        'file' => Message::FILE,
    ];

    /**
     * @var \EasyWeChat\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * Constructor.
     *
     * @codeCoverageIgnore
     *
     * @param \EasyWeChat\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;

        foreach ($this->app->extension->observers() as $observer) {
            call_user_func_array([$this, 'push'], $observer);
        }
    }

    /**
     * Handle and return response.
     *响应 服务器的验证消息.
     * @return Response
     *
     * @throws BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function serve(): Response
    {
        //通过 monolog 输出一条信息。
        /**
         * 如:http://localhost/symfony_http/request.php/
         *
         * method:POST
         * <br/>request Uri:http://localhost/symfony_http/request.php/
         * <br/>method getContentType:json
         * <br/>method getContent:{
         * "name":"Tian"
         * }
         */
        $this->app['logger']->debug('Request received:', [
            'method' => $this->app['request']->getMethod(),
            'uri' => $this->app['request']->getUri(),
            'content-type' => $this->app['request']->getContentType(),
            'content' => $this->app['request']->getContent(),
        ]);

        $response = $this->validate()->resolve();

        $this->app['logger']->debug('Server response created:', ['content' => $response->getContent()]);

        return $response;
    }

    /**
     * @return $this
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     */
    public function validate()
    {
        //isSafeMode() return false.
        if (!$this->isSafeMode()) {
            return $this;
        }
        //
        /**
         * signature: 微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
         * timestamp    时间戳
         *   nonce    随机数
         */

//        $validateStr = $this->signature([
//            $this->getToken(),//token 是在哪里设置的 ？
//            $this->app['request']->get('timestamp'),
//            $this->app['request']->get('nonce'),
//        ]);

        if ($this->app['request']->get('signature') !== $this->signature([
                $this->getToken(),
                $this->app['request']->get('timestamp'),
                $this->app['request']->get('nonce'),
            ])) {
            throw new BadRequestException('Invalid request signature.', 400);
        }

        return $this;
    }

    /**
     * Get request message.
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|string
     *
     * @throws BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function getMessage()
    {
        $message = $this->parseMessage($this->app['request']->getContent(false));//message = null

        if (!is_array($message) || empty($message)) {
            throw new BadRequestException('No message received.');
        }

        if ($this->isSafeMode() && !empty($message['Encrypt'])) {
            $message = $this->app['encryptor']->decrypt(
                $message['Encrypt'],
                $this->app['request']->get('msg_signature'),
                $this->app['request']->get('nonce'),
                $this->app['request']->get('timestamp')
            );

            // Handle JSON format.
            $dataSet = json_decode($message, true);

            if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
                return $dataSet;
            }

            $message = XML::parse($message);
        }

        $response_type = $this->app->config->get('response_type');

        return $this->detectAndCastResponseToType($message, $this->app->config->get('response_type'));
    }

    /**
     * Resolve server request and return the response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function resolve(): Response
    {
        $result = $this->handleRequest();//to = '' from = '' response = "sdfsdf"

        if ($this->shouldReturnRawResponse()) {//执行子类的 shouldReturnRawResponse 方法.
            return new Response($result['response']);//如果验证服务器 有 echostr 参数并且不为 null ，会运行到这里
        }

        return new Response(//否则
            $this->buildResponse($result['to'], $result['from'], $result['response']),
            200,
            ['Content-Type' => 'application/xml']
        );
    }

    /**
     * @return string|null
     */
    protected function getToken()
    {
        return $this->app['config']['token'];
    }

    /**
     * @param string $to
     * @param string $from
     * @param \EasyWeChat\Kernel\Contracts\MessageInterface|string|int $message
     *
     * @return string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function buildResponse(string $to, string $from, $message)
    {
        if (empty($message) || self::SUCCESS_EMPTY_RESPONSE === $message) {
            return self::SUCCESS_EMPTY_RESPONSE;
        }

        if ($message instanceof RawMessage) {
            return $message->get('content', self::SUCCESS_EMPTY_RESPONSE);
        }

        if (is_string($message) || is_numeric($message)) {
            $message = new Text((string)$message);
        }

        if (is_array($message) && reset($message) instanceof NewsItem) {
            $message = new News($message);
        }

        if (!($message instanceof Message)) {
            throw new InvalidArgumentException(sprintf('Invalid Messages type "%s".', gettype($message)));
        }

        return $this->buildReply($to, $from, $message);
    }

    /**
     * Handle request.
     *
     * @return array
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function handleRequest(): array
    {
        //取得 content 的内容，并把内容转为 相应的格式 server.php ->  'response_type' => 'array', .
        $castedMessage = $this->getMessage();//castedMessage = null .

        $messageArray = $this->detectAndCastResponseToType($castedMessage, 'array');//$messageArray = null
//---->
        //?? 的解析 , 三元运算表达式  https://www.qinziheng.com/php/4202.htm
        /***
         * c = a ?? b;
        表示如果a非空，则c = a，
        如果a为空，则 c = b；
         */
        // self::MESSAGE_TYPE_MAPPING[$messageArray['MsgType'] ?? $messageArray['msg_type'] ?? 'text'] = 2
        // $response = $this->dispatch(2, $castedMessage);

        //返回了 new FinallyResult 的 中的内容(echostr)
        $response = $this->dispatch(self::MESSAGE_TYPE_MAPPING[$messageArray['MsgType'] ?? $messageArray['msg_type'] ?? 'text'], $castedMessage);
//---->
        return [
            'to' => $messageArray['FromUserName'] ?? '',
            'from' => $messageArray['ToUserName'] ?? '',
            'response' => $response,
        ];
    }

    /**
     * Build reply XML.
     *
     * @param string $to
     * @param string $from
     * @param \EasyWeChat\Kernel\Contracts\MessageInterface $message
     *
     * @return string
     */
    protected function buildReply(string $to, string $from, MessageInterface $message): string
    {
        $prepends = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => $message->getType(),
        ];

        $response = $message->transformToXml($prepends);

        if ($this->isSafeMode()) {
            $this->app['logger']->debug('Messages safe mode is enabled.');
            $response = $this->app['encryptor']->encrypt($response);
        }

        return $response;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function signature(array $params)
    {
        sort($params, SORT_STRING);

        return sha1(implode($params));
    }

    /**
     * Parse message array from raw php input.
     *
     * @param string $content
     *
     * @return array
     *
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     */
    protected function parseMessage($content)
    {
        try {
            if (0 === stripos($content, '<')) {
                $content = XML::parse($content);
            } else {
                // Handle JSON format.
                $dataSet = json_decode($content, true);
                if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
                    $content = $dataSet;
                }
            }

            return (array)$content;
        } catch (\Exception $e) {
            throw new BadRequestException(sprintf('Invalid message content:(%s) %s', $e->getCode(), $e->getMessage()), $e->getCode());
        }
    }

    /**
     * Check the request message safe mode.
     *
     * @return bool
     */
    protected function isSafeMode(): bool
    {

        if ($this->alwaysValidate) {
            return true;
        }

        $encrypt_type = $this->app['request']->get('encrypt_type');
        $signature = $this->app['request']->get('signature');

        return $this->app['request']->get('signature') && 'aes' === $this->app['request']->get('encrypt_type');
    }

    /**
     * @return bool
     */
    protected function shouldReturnRawResponse(): bool
    {
        return false;
    }
}
