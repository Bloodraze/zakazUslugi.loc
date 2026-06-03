<?php
require __DIR__ . '/src/init.php';

use src\Feedback;
use src\exceptions\InvalidArgumentException;

$feedbackModel = new Feedback($request, $db);
$error = null;
$flash = null;

// обработка действий модерации
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

        $entity->update([
            'status' => $newStatus,
        ]);

        $flash = 'Статус отзыва обновлён';
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

// получаем все отзывы
$allFeedbacks = $feedbackModel->findAll();
?>
<?php include __DIR__ . '/src/header.php'; ?>

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
                        <input type="hidden" name="id" value="<?= $id !== null ? htmlspecialchars((string)$id) : '' ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Опубликовать</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Отклонить</button>
                    </form>
                <?php elseif ($status === 'approved'): ?>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $id !== null ? htmlspecialchars((string)$id) : '' ?>">
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Отклонить</button>
                    </form>
                <?php elseif ($status === 'rejected'): ?>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $id !== null ? htmlspecialchars((string)$id) : '' ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Опубликовать</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/src/footer.php'; ?>