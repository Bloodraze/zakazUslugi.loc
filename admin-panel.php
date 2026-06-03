<?php
require_once __DIR__ . '/src/initAdminPanel.php';
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
                                    <option value="1">На
                                        посещение</option>
                                    <option value="2">Время
                                        забронировано</option>
                                    <option value="3">Услуга оказана</option>
                                    <option value="4">Посещение перенесено</option>
                                </select>

                                <div class="help-block"></div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">найти</button> <a
                                    class="btn btn-outline-secondary" href="admin-panel.php">собросить</a>
                            </div>

                        </form>
                    </div>

                    <div id="w1" class="list-view">
                        <div class="d-flex flex-wrap justify-content-between">
                            <?php if(!empty($applications)): ?>
                                <?php foreach($applications as $app): ?>
                                    <div class="item" data-key="<?= (int)$app['id'] ?>">
                                        <div class="card" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?= htmlspecialchars($app['title'] ?? '') ?>
                                                </h5>
                                                <p class="card-text">
                                                    <?= htmlspecialchars($app['description'] ?? '') ?>
                                                </p>

                                                <div class="card-text">
                                                    <div class="opacity-50">
                                                        дата и время посещения:
                                                    </div>
                                                    <?= htmlspecialchars($app['visit_datetime'] ?? '') ?>
                                                </div>

                                                <div class="card-text">
                                                    <div class="opacity-50">
                                                        дата и время создания:
                                                    </div>
                                                    <?= htmlspecialchars($app['created_at'] ?? '') ?>
                                                </div>

                                                <div class="card-text">
                                                    <div class="opacity-50">
                                                        отправитель:
                                                    </div>
                                                    <?= htmlspecialchars($app['author_name'] ?? '') ?>
                                                </div>

                                                <div class="card-text">
                                                    <div class="opacity-50">
                                                        статус:
                                                    </div>
                                                    <?= htmlspecialchars($app['status_name'] ?? '') ?>
                                                </div>

                                                <a class="btn btn-primary"
                                                href="admin-app.php?id=<?= (int)$app['id'] ?>">просмотр</a>
                                                <?php $status = $app['status_name'] ?? ''; ?>
                                                <?php if($status === 'На посещение'): ?>
                                                    <a class="btn btn-primary" href="admin-panel.php?id=<?= (int)$app['id'] ?>&status=Время забронировано">принять</a>
                                                <?php elseif($status === 'Время забронировано' || $status === 'Посещение перенесено'): ?>
                                                    <a class="btn btn-primary" href="admin-panel.php?id=<?= (int)$app['id'] ?>&status=Услуга оказана">завершить</a>
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
        <?php include __DIR__ . '/src/footer.php'; ?>