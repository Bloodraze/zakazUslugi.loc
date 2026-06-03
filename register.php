<?php
require __DIR__ . '/src/init.php';

use src\User;

$errors = [];
$successMessage = '';
$formData = $_POST['RegisterForm'] ?? [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $user = new User($request, $db);

        $user->username = $formData['login'] ?? '';
        $user->email    = $formData['email'] ?? '';
        $user->password = $formData['password'] ?? '';
        $user->role     = 'user';

        if($user->validate($formData)){
            if($user->save()){
                $successMessage = 'Успешная регистрация!';
                $formData = [];
            }else{
                $errors[] = 'Не удалось сохранить пользователя';
            }
        }
    }catch(InvalidArgumentException $e){
        $errors[] = $e->getMessage();
    }
}

include 'src/header.php';
?>
<nav aria-label="breadcrumb">
    <ol id="w2" class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active" aria-current="page">регистрация</li>
    </ol>
</nav>
<div class="site-register">
    <h1>регистрация</h1>

    <?php if($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if($errors): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="contact-form" action="" method="post">
        <div class="mb-3 field-registerform-login required">
            <label class="form-label" for="registerform-login">логин</label>
            <input type="text" name="RegisterForm[login]"
                   value="<?= htmlspecialchars((string)($formData['login'] ?? '')) ?>">
        </div>

        <div class="mb-3 field-registerform-password required">
            <label class="form-label" for="registerform-password">пароль</label>
            <input type="text" name="RegisterForm[password]"
                   value="<?= htmlspecialchars((string)($formData['password'] ?? '')) ?>">
        </div>

        <div class="mb-3 field-registerform-email">
            <label class="form-label" for="registerform-email">Email</label>
            <input type="text" name="RegisterForm[email]"
                   value="<?= htmlspecialchars((string)($formData['email'] ?? '')) ?>">
        </div>

        <div class="mb-3 field-registerform-fio required">
            <label class="form-label" for="registerform-fio">фио</label>
            <input type="text" name="RegisterForm[fio]"
                   value="<?= htmlspecialchars((string)($formData['fio'] ?? '')) ?>">
        </div>

        <div class="mb-3 field-registerform-phone required">
            <label class="form-label" for="registerform-phone">телефон</label>
            <input type="text" name="RegisterForm[phone]"
                   value="<?= htmlspecialchars((string)($formData['phone'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">зарегестрировать</button>
        </div>
    </form>
</div>
<?php include 'src/footer.php'; ?>