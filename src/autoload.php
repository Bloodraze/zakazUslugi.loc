<?php

spl_autoload_register(function ($class){
    $path = str_replace('\\', '/', $class) . '.php';
    $file = dirname(__DIR__) . '/' . $path;
    if(file_exists($file)){
        require_once $file;    
    }
});