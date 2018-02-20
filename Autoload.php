<?php

const DEFAULT_NAMESPACE = 'Biru';

function str_replace_first($from, $to, $subject)
{
    $from = '/'.preg_quote($from, '/').'/';
    return preg_replace($from, $to, $subject, 1);
}

if (!function_exists('hash_equals')) {
    defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));
    function hash_equals($knownString, $userString)
    {
        $strlen = function ($string) {
            if (USE_MB_STRING) {
                return mb_strlen($string, '8bit');
            }
            return strlen($string);
        };
        // Compare string lengths
        if (($length = $strlen($knownString)) !== $strlen($userString)) {
            return false;
        }
        $diff = 0;
        // Calculate differences
        for ($i = 0; $i < $length; $i++) {
            $diff |= ord($knownString[$i]) ^ ord($userString[$i]);
        }
        return $diff === 0;
    }
}

spl_autoload_register(function($cls) {
    $cls = str_replace('\\', '/', $cls);
    $n = explode('/', $cls);
    if($n[0] == DEFAULT_NAMESPACE)
        $cls = str_replace_first(DEFAULT_NAMESPACE, '', $cls);

    include __DIR__.'/'.$cls.'.php';
});