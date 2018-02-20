<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 4:11 AM
 */

namespace Api;


class FacebookApi extends BaseApi
{
    const uri = 'https://graph.facebook.com/v2.6/me/messages?access_token='.ApiToken::facebook;

    public function send($to, $message, $cc = '')
    {
        $header = [
            'Content-Type' => self::$jsonMime,
        ];
        $body = [
            'recipient' => [
                'id' => $to
            ],
            'message' => [
                'text' => $message
            ]
        ];

        return self::sendRequest(self::uri, $header, $body);
    }
}