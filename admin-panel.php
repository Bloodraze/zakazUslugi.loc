<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/src/initAdminPanel.php';

$statusMap = [
    'На посещение' => 'new',
    'Время забронировано' => 'in_process',
    'Услуга оказана' => 'done',
    'Посещение перенесено' => 'change_provided',
];

if (isset($_GET['id'], $_GET['status'])) {
    $id = (int)$_GET['id'];
    $statusTitle = $_GET['status'];

    if ($id > 0 && isset($statusMap[$statusTitle])) {
        $statusValue = $statusMap[$statusTitle];
        $statusValueEsc = $db->real_escape_string($statusValue);

        $sql = "UPDATE application SET status = '{$statusValueEsc}' WHERE id = {$id}";
        $db->querySQL($sql);
        
        header('Location: admin-panel.php');
        exit;
    }
}

$selectedStatusTitle = '';
if (isset($_GET['ApplicationSearch']['status_id'])) {
    $selectedStatusTitle = trim((string)$_GET['ApplicationSearch']['status_id']);
}

if ($selectedStatusTitle !== '' && isset($statusMap[$selectedStatusTitle])) {
    $statusValue = $statusMap[$selectedStatusTitle];
    $statusValueEsc = $db->real_escape_string($statusValue);
    $sql = "SELECT * FROM application WHERE status = '{$statusValueEsc}' ORDER BY id ASC";
} else {
    $sql = "SELECT * FROM application ORDER BY id ASC";
}

$applications = $db->querySQL($sql);
if ($applications === false) {
    $applications = [];
}

$pageTitle = 'Заявки';
include __DIR__ . '/src/header.php';
?>

<nav aria-label="breadcrumb">
    <ol id="w4" class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active" aria-current="page">заявки</li>
    </ol>
</nav>

<div class="application-index">
    <h1>заявки</h1>

    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="1000">
        <div class="application-search">
            <form id="w0" action="admin-panel.php" method="get" data-pjax="1">
                <div class="form-group field-applicationsearch-status_id">
                    <label class="control-label" for="applicationsearch-status_id">статус</label>
                    <select id="applicationsearch-status_id" class="form-control"
                            name="ApplicationSearch[status_id]">
                        <option value="">выберите статус</option>
                        <option value="На посещение" <?= $selectedStatusTitle === 'На посещение' ? 'selected' : '' ?>>
                            На посещение
                        </option>
                        <option value="Время забронировано" <?= $selectedStatusTitle === 'Время забронировано' ? 'selected' : '' ?>>
                            Время забронировано
                        </option>
                        <option value="Услуга оказана" <?= $selectedStatusTitle === 'Услуга оказана' ? 'selected' : '' ?>>
                            Услуга оказана
                        </option>
                        <option value="Посещение перенесено" <?= $selectedStatusTitle === 'Посещение перенесено' ? 'selected' : '' ?>>
                            Посещение перенесено
                        </option>
                    </select>
                    <div class="help-block"></div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">найти</button>
                    <a class="btn btn-outline-secondary" href="admin-panel.php">сбросить</a>
                </div>
            </form>
        </div>

        <div id="w1" class="list-view">
            <div class="d-flex flex-wrap justify-content-between">
                <?php
                $statusLabels = [
                    'new' => 'На посещение',
                    'in_process' => 'Время забронировано',
                    'done' => 'Услуга оказана',
                    'change_provided' => 'Посещение перенесено',
                ];
                ?>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $app): ?>
                        <?php
                        $code  = $app['status'] ?? 'new';
                        $label = $statusLabels[$code] ?? $code;
                        ?>
                        <div class="item" data-key="<?= (int)$app['id'] ?>">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($app['reason'] ?? '') ?>
                                    </h5>
                                    <p class="card-text">
                                        <?= htmlspecialchars($app['text'] ?? '') ?>
                                    </p>

                                    <div class="card-text">
                                        <div class="opacity-50">дата и время посещения:</div>
                                        <?= htmlspecialchars(($app['date'] ?? '') . ' ' . ($app['time'] ?? '')) ?>
                                    </div>

                                    <div class="card-text">
                                        <div class="opacity-50">дата и время создания:</div>
                                        <?= htmlspecialchars($app['created_at'] ?? '') ?>
                                    </div>

                                    <div class="card-text">
                                        <div class="opacity-50">статус:</div>
                                        <?= htmlspecialchars($label) ?>
                                    </div>

                                    <a class="btn btn-primary"
                                       href="admin-app.php?id=<?= (int)$app['id'] ?>">просмотр</a>

                                    <?php $status = $app['status'] ?? ''; ?>
                                    <?php if ($status === 'new'): ?>
                                        <a class="btn btn-primary"
                                           href="admin-panel.php?id=<?= (int)$app['id'] ?>&status=Время забронировано">
                                            принять
                                        </a>
                                    <?php elseif ($status === 'in_process' || $status === 'change_provided'): ?>
                                        <a class="btn btn-primary"
                                           href="admin-panel.php?id=<?= (int)$app['id'] ?>&status=Услуга оказана">
                                            завершить
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Заявок нет.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/footer.php'; ?>