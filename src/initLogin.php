<?php

require __DIR__ . '/init.php';

use src\User;

$user  = new User($request, $db);
$error = null;

if ($request->isPost) {
    $post = $request->post();
    $data = $post['LoginForm'] ?? [];

    try {
        $user->login($data);
        header('Location: account.php');
        exit;
    } catch (\InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}