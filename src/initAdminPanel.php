<?php
require __DIR__ . '/init.php';

use src\User;
use src\Application;

$user = new User($request, $db);
$currentUser = $user->identity();

if($currentUser === null){
    header('Location: login.php');
    exit;
}

$user->load($currentUser);

if(!$user->isAdmin()){
    header('Location: login.php');
    exit;
}

if(isset($_GET['id'], $_GET['status'])){
    $id = (int)$_GET['id'];
    $status = trim($_GET['status']);

    if($id > 0 && $status !== ''){
        $app = new Application($request, $db);
        $app->load(['id' => $id]);
        $app->update(['status' => $status]);
    }
}

$app = new Application($request, $db);
$applications = $app->findAll();

$today = date('Y-m-d');
$applications = array_filter($applications, function($app) use ($today){
    return isset($app['date_visit']) && substr($app['date_visit'], 0, 10) === $today;
});

$statusFilter = $_GET['ApplicationSearch']['status_id'] ?? '';

if($statusFilter !== ''){
    $applications = array_filter($applications, function($app) use ($statusFilter){
        return (string)$app['status_id'] === (string)$statusFilter;
    });
}

$applications = array_values($applications);