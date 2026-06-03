<!DOCTYPE html>
<html lang="ru-RU" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $pageTitle ?? 'Приложение' ?></title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/site.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body class="d-flex flex-column h-100">
        <header id="header">
            <nav class="navbar-expand-md navbar-dark bg-dark fixed-top navbar">
                <div class="container">
                    <a class="navbar-brand" href="/">My Application</a>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav nav">
                            <li class="nav-item">
                                <a class="nav-link" href="feedback.php">отзывы</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="account.php">личный кабинет</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">выйти</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <?php if (!empty($flash)): ?>
            <div class="container" style="padding-top: 70px">
                <div class="alert alert-success mt-3">
                    <?= htmlspecialchars($flash) ?>
                </div>
            </div>
        <?php endif; ?>

        <main id="main" class="flex-shrink-0" role="main" style="padding-top: 70px">
            <div class="container">