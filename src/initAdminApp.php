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

// if(!$user->isAdmin()){
//     header('Location: login.php');
//     exit;
// }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0){
    die('Неверный id заявки');
}

$app = new Application($request, $db);
$application = $app->getById($id);

if($application === null){
    die('Заявка не найдена');
}

if (!$user->isAdmin() && (int)$application['user_id'] !== (int)$currentUser['id']) {
    http_response_code(403);
    exit('Доступ запрещён');
}

if(isset($_GET['id'], $_GET['status'])){
    $status = trim($_GET['status']);
    
    if($status !== ''){
        $app->load(['id' => $id]);
        $app->update(['status' => $status]);
        
        header("Location: admin-app.php?id={$id}");
        exit;
    }
}

$error = null;
if($request->isPost){
    $date = trim($request->post()['date'] ?? '');
    $time = trim($request->post()['time'] ?? '');
    
    try{
        if(empty($date)){
            throw new InvalidArgumentException('Не передана дата');
        }
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){
            throw new InvalidArgumentException('Неверный формат даты');
        }
        
        if(empty($time)){
            throw new InvalidArgumentException('Не передано время');
        }
        if(!preg_match('/^\d{2}:\d{2}$/', $time)){
            throw new InvalidArgumentException('Неверный формат времени');
        }
        
        $app->load($application);
        $app->update([
            'date' => $date,
            'time' => $time
        ]);
        
        $application = $app->getById($id);
        
    } catch(InvalidArgumentException $e){
        $error = $e->getMessage();
    }
}