<?php

use src\services\Request;

require 'autoload.php';
require 'config.php';


if(session_status() === PHP_SESSION_NONE){
    session_start();
}

try{
    $request = new Request();
    $db = new src\services\DB($dbOptions);
    $user = new src\User($request, $db);
    $identity = $user->identity();
    if($identity !==null){
        $user->load($identity);
        $user->isGuest = false;
        $user->isAdmin = $user->isAdmin();
    }
}catch(\exceptions\DBException $e){
    echo $e->getMessage();
    exit();
}