<?php

require_once __DIR__ . '/init.php';

$pageTitle = "Личный кабинет";

$userId = $_SESSION['user_id'] ?? null;
$userApplications = [];
$targetUserId = [1, 2, 3];
$appModel = new src\Application($request, $db);
foreach ($targetUserId as $id){
    $apps = $appModel->findByColumn('user_id', $id);
    if(!empty($apps) && is_array($apps)){
        $userApplications = array_merge($userApplications, $apps);
    }
}