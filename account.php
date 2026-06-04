<?php

require_once 'src/init_account.php';
require_once 'src/init_add-application.php';

$currentUserId = $_SESSION['user_id'] ?? null;

/* УДАЛЕНИЕ ЗАЯВКИ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];

    if ($deleteId > 0 && $currentUserId !== null && !empty($userApplications)) {
        foreach ($userApplications as $application) {
            if (
                (int)$application->id === $deleteId &&
                (int)$application->user_id === (int)$currentUserId &&
                ($application->status ?? '') === 'new'
            ) {
                $application->delete();
                break;
            }
        }
    }

    header('Location: account.php');
    exit;
}

include 'src/header.php';
?>

<nav aria-label="breadcrumb">
    <ol id="w4" class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active" aria-current="page">заявки</li>
    </ol>
</nav>

<div class="application-index">
    <h1>заявки</h1>

    <p>
        <a class="btn btn-success" href="add-application.php">подать заявку</a>
    </p>
    <p>
        <a class="btn btn-primary" href="change-password.php">сменить пароль</a>
    </p>

    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="1000">
        <div class="application-search">
            <form id="w0" action="account.php" method="POST" data-pjax="1">
                <div class="form-group field-applicationsearch-status_id">
                    <label class="control-label" for="applicationsearch-status_id">статус</label>
                    <select id="applicationsearch-status_id" class="form-control" name="ApplicationSearch[status_id]">
                        <option value="">выберите статус</option>
                        <option value="1">На посещение</option>
                        <option value="2">Время забронировано</option>
                        <option value="3">Услуга оказана</option>
                        <option value="4">Посещение перенесено</option>
                    </select>
                    <div class="help-block"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">найти</button>
                    <a class="btn btn-outline-secondary" href="./">сбросить</a>
                </div>
            </form>
        </div>

        <div id="w1" class="list-view">
            <div class="d-flex flex-wrap justify-content-between layout-card">
                <?php if (empty($userApplications)): ?>
                    <p>Заявок пока нет</p>
                <?php else: ?>
                    <?php foreach ($userApplications as $application): ?>
                        <div class="item" data-key="<?= (int)$application->id ?>">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($application->reason ?? 'Без названия') ?>
                                    </h5>

                                    <p class="card-text">
                                        <?= htmlspecialchars($application->text ?? '') ?>
                                    </p>

                                    <div class="card-text">
                                        <div class="opacity-50">дата и время посещения:</div>
                                        <?= htmlspecialchars($application->date ?? '') ?>
                                        <?= htmlspecialchars($application->time ?? '') ?>
                                    </div>

                                    <div class="card-text">
                                        <div class="opacity-50">дата и время создания:</div>
                                        <?= htmlspecialchars($application->created_at ?? '') ?>
                                    </div>

                                    <div class="card-text">
                                        <div class="opacity-50">статус:</div>
                                        <?= htmlspecialchars($application->status ?? 'new') ?>
                                    </div>

                                    <a class="btn btn-primary" href="admin-app.php?id=<?= (int)$application->id ?>">просмотр</a>

                                    <?php if (($application->status ?? '') === 'new'): ?>
                                        <form action="account.php" method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= (int)$application->id ?>">
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Удалить заявку?')">
                                                удалить
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'src/footer.php'; ?>