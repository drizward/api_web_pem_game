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

    /// Parameter
    ///     $to      - ID of the chat room NOT THE USER ID OR USERNAME
    ///     $message - The message to send
    ///     $cc      - Unused, only for email
    ///
    /// Use as Example:
    ///     $res = $api->catchHook();
    ///     $id = $res->message->chat->id;
    ///     $api->send($id, 'HALOOO');
    ///
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

    /// For response and pattern look at:
    ///     https://core.telegram.org/bots/api
    ///     NOTE: Response are served as object instead of assoc array
    ///     NOTE: Telegram has no secret code or salt
    ///
    /// Parameter:
    ///     $arr - NO USE, SINCE TELEGRAM DIDN'T SPECIFY ANY TYPE
    ///
    /// Use as Example:
    ///     $api = new TelegramApi();
    ///     $res = $api->catchHook();
    ///     echo $res->message->chat->id # Get ID of the chat room
    ///
    public function catchHook($arr = null) {
        $data = json_decode(file_get_contents("php://input"), true);
        return (object) $data;
    }
}