<?php

require_once __DIR__ . '/init.php';

$pageTitle = "Личный кабинет";

$userId = $_SESSION['user_id'] ?? null;
$role = $_SESSION['role'] ?? 'user';

$userApplications = [];
$appModel = new src\Application($request, $db);

if ($role === 'admin') {
    $apps = $appModel->findAll();
} elseif ($userId !== null) {
    $apps = $appModel->findByColumn('user_id', (int)$userId);
} else {
    $apps = [];
}

if (!empty($apps) && is_array($apps)) {
    $userApplications = $apps;
}