<?php

const DEFAULT_NAMESPACE = 'Api';

spl_autoload_register(function($cls) {
    $cls = str_replace('\\', '/', $cls);
    $n = explode('/', $cls);
    if($n[0] == DEFAULT_NAMESPACE)
        $cls = str_replace(DEFAULT_NAMESPACE, '', $cls);

    include __DIR__.$cls.'.php';
});