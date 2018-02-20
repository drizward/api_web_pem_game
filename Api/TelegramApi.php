<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 4:35 AM
 */

namespace Api;


class TelegramApi extends BaseApi
{
    const auth = 'bot'.ApiToken::telegram;
    const uri = 'https://api.telegram.org/'.self::auth.'/sendMessage';

    public function send($to, $message, $cc = '')
    {
        $header = [
            'Content-Type' => self::$jsonMime
        ];
        $body = [
            'chat_id' => $to,
            'text' => $message
        ];

        return self::sendRequest(self::uri, $header, $body);
    }
}