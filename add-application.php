<?php
include 'src/header.php';

require_once 'src/init_add-application.php';

?>
    <?php if(isset($_SESSION['flash']) && $_SESSION['flash']): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['flash']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <div class="feedback-index p-3">
        <form id="w0" action="add-application.php" method="POST">
            <input type="hidden" name="_csrf"
                value="Y8NMvvT3LR7_0FE4QlfcxYPKc6Y2OK44IrCGNdMqbagnjTjstcRneKWCP3EvEIap9vpLxXJbx1sS4-9C6ksc4w==">
            <div class="mb-3 field-feedback-date required">
                <label class="form-label" for="app-date">Выберите дату</label>
                <input type="date" id="app-date" class="form-control" name="date" value="" aria-required="true">
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3 field-feedback-time required">
                <label class="form-label" for="app-time">Выберите время посещения</label>
                <input type="time" id="app-time" class="form-control" name="time" value="" aria-required="true">
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3 field-feedback-reason required">
                <label class="form-label" for="feedback-reason">Причина посещения (кратко)</label>
                <input type="text" id="feedback-reason" class="form-control" name="reason" value="" aria-required="true">
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3 field-feedback-text required">
                <label class="form-label" for="feedback-text">Причина посещения (подробно)</label>
                <textarea id="feedback-text" class="form-control" name="text" 
                    aria-required="true"></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">отправить заявку</button>
            </div>
        </form>
    </div>
<?php include 'src/footer.php'; ?>