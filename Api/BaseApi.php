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

    public function makeRequest($header, $body, $method = "POST") {
        return stream_context_create([
            'http' => [
                'header' => str_replace("=", ": ", http_build_query($header, '', '\r\n')),
                'method' => $method,
                'body' => json_encode($body)
            ]
        ]);
    }

    public function sendRequest($uri, $context) {
        return file_get_contents($uri, false, $context);
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

    public abstract function send($to, $message, $cc = '');
}