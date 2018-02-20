<?php

const DEFAULT_NAMESPACE = 'Biru';

function str_replace_first($from, $to, $subject)
{
    $from = '/'.preg_quote($from, '/').'/';
    return preg_replace($from, $to, $subject, 1);
}

spl_autoload_register(function($cls) {
    $cls = str_replace('\\', '/', $cls);
    $n = explode('/', $cls);
    if($n[0] == DEFAULT_NAMESPACE)
        $cls = str_replace_first(DEFAULT_NAMESPACE, '', $cls);

    include __DIR__.'/'.$cls.'.php';
});