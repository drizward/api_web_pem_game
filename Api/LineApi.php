<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 3:11 AM
 */

namespace Api;


class LineApi extends BaseApi
{
    const auth = "Bearer ".ApiToken::line;
    const uri = 'https://api.line.me/v2/bot/message/push';

    public function send($to, $message, $cc = '')
    {
        $header = [
            'Authorization' => self::auth,
            'Content-Type' => self::$jsonMime
        ];
        $body = [
            'to' => $to,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ];

        return self::sendRequest(self::uri, $header, $body);
    }
}