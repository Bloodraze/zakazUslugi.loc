<?php

use src\Application;
require_once 'src/services/Request.php';

require 'init.php';

$page = 'account.php';

$app = new Application($request, $db);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['auth_error'] = 'Для отправки заявки необходимо авторизоваться';
    header('Location: /login.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    try {
        if ($app->saveApplication($userId, $_POST)) {
            $_SESSION['flash'] = 'Заявка создана';
            header('Location: account.php');
            exit;
        }
    } catch (\InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}