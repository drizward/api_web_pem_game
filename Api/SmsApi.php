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
    const uri = 'https://rest.nexmo.com/sms/json';
    public function send($to, $message, $sender = 'Pemrograma Game A')
    {
        $header = [
            'Content-Type' => self::$defaultMime
        ];
        $body = [
            'from' => $sender,
            'text' => $message,
            'to' => $to,
            'api_key' => ApiToken::sms,
            'api_secret' => ApiToken::smsSecret
        ];

        return self::sendRequest(self::uri, $header, $body);
    }
}