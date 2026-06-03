<?php

use src\Feedback;
use src\services\Request;
use src\services\DB;

require __DIR__ . '/init.php';

$page = 'feedback.php';

$feedback = new Feedback($request, $db);
$error = null;
$flash = null;

if($request->isPost){
    $post = $request->post();
    $file = $_FILES['image_file'] ?? null;

    try{
        $feedback->loadFromForm($post, $file);
        $feedback->validate();
        if($feedback->save()){
            $_SESSION['flash'] = 'Отзыв добавлен';
            header('Location: feedback.php');
            exit;
        }
    }catch(InvalidArgumentException $e){
        $error = $e->getMessage();
        $_SESSION['flash'] = null;
    }
}

if(isset($_SESSION['flash']) && $_SESSION['flash']){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

$feedbackModel = new Feedback($request, $db);
$feedbacks = $feedbackModel->findApproved();