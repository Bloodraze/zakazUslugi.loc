<?php

require 'init.php';

if($request->isPost){
    $user->load($request->post());
    
    try{
        $user->login($_POST['LoginForm']);
        $_SESSION['user_id'] = $user->id;
        header("Location: index1.php");
        exit;
    } catch(src\exceptions\InvalidArgumentException $e){
        $error = $e->getMessage();
    }
}