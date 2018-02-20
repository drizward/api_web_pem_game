<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 3:48 AM
 */

namespace Api;


class WhatsappApi extends BaseApi
{
    const uri = 'https://www.waboxapp.com/api/send/chat?';

    public function send($to, $message, $cc = '')
    {
        $header = null;
        $params = [
            'token' => ApiToken::whatsapp,
            'uid' => ApiToken::whatsappNumber,
            'phone' => $to,
            'custom_uid' => self::randomize(10),
            'text' => urlencode($message)
        ];
        $body = null;

        return self::sendRequest(self::uri.http_build_query($params), $header, $body);
    }
}