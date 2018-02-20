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

    /// Parameter
    ///     $to      - Unique ID of user which generated for the chat room, NOT THE REAL USER ID
    ///     $message - The message to send
    ///     $cc      - Unused, only for email
    ///
    /// Use as Example:
    ///     SEE BELOW
    ///
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

    /// For response and pattern look at:
    ///     https://developers.line.me/en/docs/messaging-api/reference/
    ///     NOTE: Response are served as object instead of assoc array
    ///
    /// Parameter:
    ///     $arr - Array that consists of another array with property 'event' and 'callback'.
    ///            Do not specify any to catch all response
    ///
    /// Use as Example:
    ///     $api = new LineApi();
    ///     $api->catchHook([
    ///         [
    ///             'event' => 'follow',
    ///             'callback' => function($data) use ($api) {
    ///                 $api->send($data->source->id, 'sukses');
    ///             }
    ///         ]
    ///     ]);
    public function catchHook($arr = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            error_log("Method not allowed");
            exit();
        }

        $entityBody = file_get_contents('php://input');
        if (strlen($entityBody) === 0) {
            http_response_code(400);
            error_log("Missing request body");
            exit();
        }

        if (!hash_equals($this->sign($entityBody), $_SERVER['HTTP_X_LINE_SIGNATURE'])) {
            http_response_code(400);
            error_log("Invalid signature value");
            exit();
        }

        $data = json_decode($entityBody, true);
        if (!isset($data['events'])) {
            http_response_code(400);
            error_log("Invalid request body: missing events property");
            exit();
        }

        if(is_null($arr))
            return $data['events'];

        foreach($data['events'] as $d) {
            foreach($arr as $a) {
                if($a['event'] == $d['type']) {
                    return $a['callback']((object)$d);
                }
            }
        }

        return false;
    }

    public function getProfile($id) {
        $header = [
            'Authorization' => self::auth,
            'Content-Type' => self::$urlencodedMime///
        ];

        return self::sendRequest('https://api.line.me/v2/bot/profile/'.$id, $header, null, 'GET');
    }

    private function sign($body) {
        $hash = hash_hmac('sha256', $body, ApiToken::lineSecret, true);
        $signature = base64_encode($hash);
        return $signature;
    }
}