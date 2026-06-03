<?php
require __DIR__ . '/src/initFeedback.php';
include __DIR__ . '/src/header.php';
?>

<h1>Добавить отзыв</h1>

<?php if (!empty($flash)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="feedback-index p-3">
    <form id="w0" action="" method="post" enctype="multipart/form-data">
        <div class="mb-3 field-feedback-fio required">
            <label class="form-label" for="feedback-fio">фио</label>
            <input type="text"
                   id="feedback-fio"
                   class="form-control"
                   name="fio"
                   aria-required="true">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3 field-feedback-phone required">
            <label class="form-label" for="feedback-phone">телефон</label>
            <input type="text"
                   id="feedback-phone"
                   class="form-control"
                   name="phone"
                   aria-required="true">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3 field-feedback-text required">
            <label class="form-label" for="feedback-text">отзыв</label>
            <textarea id="feedback-text"
                      class="form-control"
                      name="text"
                      aria-required="true"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3 field-feedback-imagefile">
            <label class="form-label" for="feedback-imagefile">фото</label>
            <input type="file"
                   id="feedback-imagefile"
                   class="form-control"
                   name="image_file">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3 field-feedback-agree required">
            <div class="form-check">
                <input type="checkbox"
                       id="feedback-agree"
                       class="form-check-input"
                       name="agree"
                       value="1"
                       aria-required="true">
                <label class="form-check-label" for="feedback-agree">
                    Согласие на обработку персональных данных
                </label>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">отправить</button>
        </div>
    </form>
</div><!-- feedback-index -->

<hr>

<h2>Отзывы</h2>

<?php if (!empty($feedbacks)): ?>
    <div class="d-flex flex-wrap gap-3">
        <?php foreach ($feedbacks as $item): ?>
            <div class="card mb-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($item['fio'] ?? '') ?>
                    </h5>
                    <p class="card-text">
                        <?= htmlspecialchars($item['text'] ?? '') ?>
                    </p>

                    <?php if (!empty($item['image_file'])): ?>
                        <img src="<?= htmlspecialchars($item['image_file']) ?>"
                             class="card-img-bottom"
                             alt=""
                             style="max-width: 100%; height: auto;">
                    <?php endif; ?>

                    <div class="small text-muted mt-2">
                        <?= htmlspecialchars($item['create_at'] ?? '') ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Отзывов пока нет.</p>
<?php endif; ?>

<?php include __DIR__ . '/src/footer.php'; ?>