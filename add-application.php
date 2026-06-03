<?php
include 'src/header.php';

require_once 'src/init_add-application.php';

?>
            <?php if(isset($_SESSION['flash'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            <div class="feedback-index p-3">
                <form id="w0" action="add-application.php" method="POST">
                    <input type="hidden" name="_csrf"
                        value="Y8NMvvT3LR7_0FE4QlfcxYPKc6Y2OK44IrCGNdMqbagnjTjstcRneKWCP3EvEIap9vpLxXJbx1sS4-9C6ksc4w==">
                    <div class="mb-3 field-feedback-fio required">
                        <label class="form-label" for="feedback-fio">Выберите дату</label>
                        <input type="date" id="app-date" class="form-control" name="date" value="" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 field-feedback-fio required">
                        <label class="form-label" for="feedback-fio">Выберите время посещения</label>
                        <input type="time" id="app-time" class="form-control" name="time" value="" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 field-feedback-fio required">
                        <label class="form-label" for="feedback-fio">Причина посещения (кратко)</label>
                        <input type="text" id="feedback-fio" class="form-control" name="reason" value=""  aria-required="true">
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
            </div><!-- feedback-index -->
        </div>
    </main>
    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">


            </div>
        </div>
    </footer>

  
    <script src="js/bootstrap.bundle.js"></script>

</body>

</html>