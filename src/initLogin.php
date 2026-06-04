<?php
require 'init.php';

if($request->isPost){
    $user->load($request->post());
    
    try{
        $user->login($_POST['LoginForm']);
        if ($user->role === 'client') {
            header("Location: account.php");
        } elseif ($user->role === 'admin') {
            header("Location: admin-panel.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } catch(src\exceptions\InvalidArgumentException $e){
        $error = $e->getMessage();
    }
}