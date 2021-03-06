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

    /// Parameter
    ///     $to         - PSID generated by chat room or phone_number, NOT FACEBOOK ID
    ///     $message    - The message to send
    ///     $isPhoneNum - Is $to parameter are phone number, false as default
    ///
    /// Use as Example:
    ///     SEE BELOW
    ///
    public function send($to, $message, $isPhoneNum = false)
    {
        if($isPhoneNum)
            $recipient = [ 'phone_number' => $to ];
        else
            $recipient = [ 'id' => $to ];

        $header = [
            'Content-Type' => self::$jsonMime,
        ];
        $body = [
            'messaging_type' => 'UPDATE',
            'recipient' => $recipient,
            'message' => [
                'text' => $message
            ]
        ];

        return self::sendRequest(self::uri, $header, $body);
    }

    /// For response and pattern look at:
    ///     https://developers.facebook.com/docs/messenger-platform/webhook/
    ///     NOTE: Response are served as object instead of assoc array
    ///
    /// Parameter:
    ///     $arr - Array that consists of another array with property 'event' and 'callback'.
    ///            Do not specify any to catch all response
    ///            For events, refer to: https://developers.facebook.com/docs/messenger-platform/reference/webhook-events/
    ///
    /// Use as Example:
    ///     $api = new FacebookApi();
    ///     $api->catchHook([
    ///         [
    ///             'event' => 'message',
    ///             'callback' => function($data) use ($api) {
    ///                 $api->send($data[0]->messaging[0]->sender->id, 'Thank you to have sent me a message');
    ///             }
    ///         ]
    ///     ]);
    public function catchHook($arr = null) {
        if(isset($_REQUEST['hub_verify_token'])) {
            if (ApiToken::facebookVerify != $_REQUEST['hub_verify_token']) {
                http_response_code(400);
                error_log("Invalid signature value");
                exit();
            }
            if(isset($_REQUEST['hub_challenge'])) {
                echo $_REQUEST['hub_challenge'];
                return true;
            }
        }

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

        $data = json_decode($entityBody, true);
        if (!isset($data['object']) || $data['object'] != 'page') {
            http_response_code(400);
            error_log("Invalid request body: data is malformed");
            exit();
        }

        if(is_null($arr))
            return $data['entry'];

        foreach($data['entry'] as $d) {
            foreach($arr as $a) {
                if(isset($d[$a['event']])) {
                    return $a['callback'](json_decode(json_encode($d)));
                }
            }
        }

        return false;
    }
}