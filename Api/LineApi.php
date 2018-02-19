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
    const auth = 'Bearer '.ApiToken::line;
    const uri = 'https://api.line.me/v2/bot/message/push';

    public function send($to, $message, $cc = '')
    {
        $header = [
            'Content-Type' => self::$jsonMime,
            'Authorization' => self::auth
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

        $req = self::makeRequest($header, $body);
        return self::sendRequest(self::uri, $req);
    }
}