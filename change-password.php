<?php
require_once __DIR__ . '/src/init.php';

$pageTitle = 'Смена пароля';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $retypePassword = $_POST['retypePassword'] ?? '';
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        $error = 'Вы не авторизованы';
    } elseif (empty($currentPassword) || empty($newPassword) || empty($retypePassword)) {
        $error = 'Заполните все поля';
    } elseif ($newPassword !== $retypePassword) {
        $error = 'Новые пароли не совпадают';
    } elseif (strlen($newPassword) < 6) {
        $error = 'Новый пароль должен быть не менее 6 символов';
    } else {
        $stmt = $db->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $dbPassword = $row['password'] ?? null;
        } else {
            $dbPassword = null;
        }

        if ($dbPassword === null) {
            $error = 'Пользователь не найден или пароль не установлен';
        } elseif ($currentPassword !== $dbPassword) {
            $error = 'Неверный текущий пароль';
        } else {
            $stmtUpd = $db->prepare("UPDATE user SET password = ? WHERE id = ?");
            $stmtUpd->bind_param('si', $newPassword, $userId);
            $stmtUpd->execute();

            $flash = 'Пароль успешно изменён';
            header("Location: account.php");
            exit;
        }
    }
}

include __DIR__ . '/src/header.php';
?>

<?php if (!empty($error)): ?>
    <div class="bg-warning p-3 mt-3"><?= htmlspecialchars($error) ?></div>
<?php endif ?>

<div class="feedback-index p-3 mt-3">
    <form id="w0" action="" method="post">
        <div class="mb-3">
            <label class="form-label" for="app-current-password">Введите текущий пароль</label>
            <input type="password" id="app-current-password" class="form-control"
                   name="currentPassword" aria-required="true">
        </div>

        <div class="mb-3">
            <label class="form-label" for "app-new-password">Введите новый пароль</label>
            <input type="password" id="app-new-password" class="form-control"
                   name="newPassword" aria-required="true">
        </div>

        <div class="mb-3">
            <label class="form-label" for="app-retype-password">Подтвердите новый пароль</label>
            <input type="password" id="app-retype-password" class="form-control"
                   name="retypePassword" aria-required="true">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">изменить пароль</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/src/footer.php'; ?>