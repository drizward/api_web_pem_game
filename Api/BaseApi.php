<?php
/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 2/20/2018
 * Time: 3:08 AM
 */

namespace Api;

abstract class BaseApi
{
    public static $jsonMime = "application/json";
    public static $defaultMime = "application/octet-stream";
    public static $urlencodedMime = "application/x-www-form-urlencoded";

    public function sendRequest($uri, $header = null, $body = null, $method = "POST") {
        $curl = curl_init();
        if(is_null($header))
            $header = ['Content-Type' => self::$defaultMime];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => is_null($body) ? null : json_encode($body),
            CURLOPT_HTTPHEADER => self::serializeHeader($header)
        ));

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $err? "cURL Error #:" . $err : $res;
    }

    public static function randomize($max, $numOnly = false) {
        $r = '';
        for($i = 0; $i < $max; $i++) {
            $c = '\0';
            while(!ctype_alnum($c))
                $c = chr(rand(48, 90)); // 0 - Z

            $r .= $c;
        }
        return $r;
    }

    public static function serializeHeader($header) {
        $rt = [];
        foreach($header as $k => $v) {
            $rt[] = "$k: $v";
        }
        print_r($rt);
        return $rt;
    }

    public abstract function send($to, $message, $cc = '');
}