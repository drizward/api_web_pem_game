<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 5:12 AM
 */

namespace Api;


class SmsApi extends BaseApi
{
    const uri = 'https://textbelt.com/text';
    public function send($to, $message, $cc = '')
    {
        $header = [
            'Content-Type' => self::$defaultMime
        ];
        $body = [
            'phone' => $to,
            'message' => $message,
            'key' => ApiToken::sms
        ];

        $req = self::makeRequest($header, $body);
        return self::sendRequest(self::uri, $req);
    }
}