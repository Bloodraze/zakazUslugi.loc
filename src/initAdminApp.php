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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Неверный id заявки');
}

$app = new Application($request, $db);
$application = $app->getById($id);

if ($application === null) {
    die('Заявка не найдена');
}

if (!$user->isAdmin() && (int)$application['user_id'] !== (int)$currentUser['id']) {
    http_response_code(403);
    exit('Доступ запрещён');
}

$authorModel = new User($request, $db);
$author = $authorModel->getById((int)$application['user_id']);
$application['author_name'] = $author['name'] ?? ($author['fio'] ?? 'Не известно');

if (isset($_GET['id'], $_GET['status'])) {
    $status = trim($_GET['status']);

    if ($status !== '') {
        $app->load(['id' => $id]);
        $app->update(['status' => $status]);

        header("Location: admin-app.php?id={$id}");
        exit;
    }
}

$error = null;
$successMessage = null;

if ($request->isPost) {
    $date = trim($request->post()['date'] ?? '');
    $time = trim($request->post()['time'] ?? '');

    try {
        if (!$user->isAdmin() && ($application['status'] ?? '') !== 'new') {
            throw new InvalidArgumentException('Заявка уже принята, изменить дату и время нельзя');
        }

        if (empty($date)) {
            throw new InvalidArgumentException('Не передана дата');
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new InvalidArgumentException('Неверный формат даты');
        }

        if (empty($time)) {
            throw new InvalidArgumentException('Не передано время');
        }
        if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
            throw new InvalidArgumentException('Неверный формат времени');
        }

        $app->load($application);
        $app->update([
            'date' => $date,
            'time' => $time,
            'status' => 'change_provided',
        ]);

        $application = $app->getById($id);
        $author = $authorModel->getById((int)$application['user_id']);
        $application['author_name'] = $author['name'] ?? ($author['fio'] ?? 'Не известно');

        $successMessage = 'Дата и время посещения изменены, статус: Посещение перенесено';
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

$canEditDateTime = $user->isAdmin() || ($application['status'] ?? '') === 'new';

$statusLabels = [
    'new' => 'На посещение',
    'in_process' => 'Время забронировано',
    'done' => 'Услуга оказана',
    'change_provided' => 'Посещение перенесено',
];

$code = $application['status'] ?? 'new';
$application['status_name'] = $statusLabels[$code] ?? $code;