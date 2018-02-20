<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 5:08 AM
 */

namespace Api;


class EmailApi extends BaseApi
{
    const defaultCc = 'Email Verification for Registration';
    public function send($to, $message, $cc = '')
    {
        $headers =  'From: '.ApiToken::emailSender. "\r\n" .
                    'Reply-To: '.ApiToken::emailSender. "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        return mail($to, $cc, $message, $headers);
    }
}