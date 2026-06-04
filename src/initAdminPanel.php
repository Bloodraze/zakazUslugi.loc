<?php
require __DIR__ . '/init.php';

use src\User;
use src\Application;

// 1. Определяем текущего пользователя
$user = new User($request, $db);
$currentUser = $user->identity();

if ($currentUser === null) {
    header('Location: login.php');
    exit;
}

$user->load($currentUser);

// 2. Проверяем, что это админ
if (!$user->isAdmin()) {
    header('Location: login.php');
    exit;
}

// 3. Смена статуса ЗДЕСЬ УЖЕ НЕ ДЕЛАЕТСЯ
// Весь код вида:
// if(isset($_GET['id'], $_GET['status'])) { ... $app->update(['status' => $status]); }
// НУЖНО УДАЛИТЬ/ЗАКОММЕНТИРОВАТЬ,
// потому что статус меняем ТОЛЬКО в admin-panel.php через ENUM.

// 4. Загружаем все заявки
$app = new Application($request, $db);
$applications = $app->findAll();

// В твоих заявках поля даты/времени называются `date` и `time`.
// В старом коде было `date_visit` и `status_id`, это не совпадает со структурой.
// Если хочешь оставить фильтр по сегодняшней дате:
$today = date('Y-m-d');
$applications = array_filter($applications, function ($app) use ($today) {
    return isset($app['date']) && substr($app['date'], 0, 10) === $today;
});

// Фильтр по статусу (если нужен именно здесь — через ENUM)
$statusFilter = $_GET['ApplicationSearch']['status_id'] ?? '';

if ($statusFilter !== '') {
    // сюда уже должна приходить строка ENUM: new / in_process / done / change_provided
    $applications = array_filter($applications, function ($app) use ($statusFilter) {
        return isset($app['status']) && (string)$app['status'] === (string)$statusFilter;
    });
}

// Переиндексация
$applications = array_values($applications);