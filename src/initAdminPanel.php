<?php
require __DIR__ . '/init.php';

use src\User;
use src\Application;

$user = new User($request, $db);
$currentUser = $user->identity();

if ($currentUser === null) {
    header('Location: login.php');
    exit;
}

$user->load($currentUser);

if (!$user->isAdmin()) {
    header('Location: login.php');
    exit;
}

$app = new Application($request, $db);
$applications = $app->findAll();
$today = date('Y-m-d');
$applications = array_filter($applications, function ($app) use ($today) {
    return isset($app['date']) && substr($app['date'], 0, 10) === $today;
});
$statusFilter = $_GET['ApplicationSearch']['status_id'] ?? '';

if ($statusFilter !== '') {
    $applications = array_filter($applications, function ($app) use ($statusFilter) {
        return isset($app['status']) && (string)$app['status'] === (string)$statusFilter;
    });
}

$applications = array_values($applications);