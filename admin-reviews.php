<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /');
    exit;
}

require __DIR__ . '/src/init.php';

use src\Feedback;
use src\exceptions\InvalidArgumentException;

$feedbackModel = new Feedback($request, $db);

$error = null;
$flash = null;
$allFeedbacks = [];

if ($request->isPost) {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    $feedback = $feedbackModel->getById($id);
    if ($feedback === null) {
        header('Location: 404.php');
        exit;
    }

    try {
        $entity = new Feedback($request, $db);
        $entity->load($feedback);

        if ($action === 'approve') {
            $newStatus = 'approved';
        } elseif ($action === 'reject') {
            $newStatus = 'rejected';
        } else {
            throw new InvalidArgumentException('Неизвестное действие');
        }

        $entity->update(['status' => $newStatus]);
        $flash = 'Статус отзыва обновлён';
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

$allFeedbacks = $feedbackModel->findAll();
if (!is_array($allFeedbacks)) {
    $allFeedbacks = [];
}

$pageTitle = 'Модерация отзывов';
include __DIR__ . '/src/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="admin-panel.php">Панель управления</a></li>
        <li class="breadcrumb-item active" aria-current="page">Модерация отзывов</li>
    </ol>
</nav>

<h1>Модерация отзывов</h1>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>ФИО</th>
        <th>Текст</th>
        <th>Статус</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($allFeedbacks)): ?>
        <?php foreach ($allFeedbacks as $item): ?>
            <?php
            $id = $item['id'] ?? null;
            $fio = $item['fio'] ?? '';
            $text = $item['text'] ?? '';
            $status = $item['status'] ?? '';
            ?>
            <tr>
                <td><?= $id !== null ? htmlspecialchars((string)$id) : '' ?></td>
                <td><?= htmlspecialchars((string)$fio) ?></td>
                <td><?= htmlspecialchars((string)$text) ?></td>
                <td><?= htmlspecialchars((string)$status) ?></td>
                <td>
                    <?php if ($status === 'new'): ?>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="id"
                                   value="<?= $id !== null ? htmlspecialchars((string)$id) : '' ?>">
                            <button type="submit" name="action" value="approve"
                                    class="btn btn-success btn-sm">Опубликовать</button>
                            <button type="submit" name="action" value="reject"
                                    class="btn btn-danger btn-sm">Отклонить</button>
                        </form>
                    <?php elseif ($status === 'approved'): ?>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="id"
                                   value="<?= $id !== null ? htmlspecialchars((string)$id) : '' ?>">
                            <button type="submit" name="action" value="reject"
                                    class="btn btn-danger btn-sm">Отклонить</button>
                        </form>
                    <?php elseif ($status === 'rejected'): ?>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="id"
                                   value="<?= $id !== null ? htmlspecialchars((string)$id) : '' ?>">
                            <button type="submit" name="action" value="approve"
                                    class="btn btn-success btn-sm">Опубликовать</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">Отзывов пока нет</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/src/footer.php'; ?>