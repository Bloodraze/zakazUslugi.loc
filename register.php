<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}

require __DIR__ . '/src/init.php';

use src\User;

$errors = [];
$successMessage = '';
$formData = $_POST['RegisterForm'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User($request, $db);
        $user->username = $formData['login'] ?? '';
        $user->email = $formData['email'] ?? '';
        $user->password = $formData['password'] ?? '';
        $user->fio = $formData['fio'] ?? '';
        $user->phone = $formData['phone'] ?? '';
        $user->role = 'client';

        if ($user->validate($formData)) {
            if ($user->save()) {
                $successMessage = 'Клиент успешно зарегистрирован';
                $formData = [];
            } else {
                $errors[] = 'Не удалось сохранить пользователя';
            }
        }
    } catch (\InvalidArgumentException $e) {
        $errors[] = $e->getMessage();
    }
}

$pageTitle = 'Регистрация клиента';
include 'src/header.php';
?>
<nav aria-label="breadcrumb">
    <ol id="w2" class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="admin-panel.php">Панель управления</a></li>
        <li class="breadcrumb-item active" aria-current="page">регистрация клиента</li>
    </ol>
</nav>

<div class="site-register">
    <h1>регистрация клиента</h1>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="contact-form" action="" method="post">
        <div class="mb-3 field-registerform-login required">
            <label class="form-label" for="registerform-login">логин</label>
            <input type="text" name="RegisterForm[login]"
                   value="<?= htmlspecialchars((string)($formData['login'] ?? '')) ?>"
                   class="form-control" required>
        </div>

        <div class="mb-3 field-registerform-password required">
            <label class="form-label" for="registerform-password">пароль</label>
            <input type="password" name="RegisterForm[password]"
                   value="<?= htmlspecialchars((string)($formData['password'] ?? '')) ?>"
                   class="form-control" required>
        </div>

        <div class="mb-3 field-registerform-email">
            <label class="form-label" for="registerform-email">Email</label>
            <input type="text" name="RegisterForm[email]"
                   value="<?= htmlspecialchars((string)($formData['email'] ?? '')) ?>"
                   class="form-control">
        </div>

        <div class="mb-3 field-registerform-fio required">
            <label class="form-label" for="registerform-fio">ФИО</label>
            <input type="text" name="RegisterForm[fio]"
                   value="<?= htmlspecialchars((string)($formData['fio'] ?? '')) ?>"
                   class="form-control" required>
        </div>

        <div class="mb-3 field-registerform-phone required">
            <label class="form-label" for="registerform-phone">телефон</label>
            <input type="text" name="RegisterForm[phone]"
                   value="<?= htmlspecialchars((string)($formData['phone'] ?? '')) ?>"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">зарегистрировать</button>
        </div>
    </form>
</div>

<?php include 'src/footer.php'; ?>