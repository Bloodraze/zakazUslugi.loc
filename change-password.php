<?php

include __DIR__ . '/src/header.php';

?>
            <?php if (!empty($error)): ?>
                <div class="bg-warning"> <?= $error ?></div>
            <?php endif ?>

            <div class="feedback-index p-3">
                <form id="w0" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf"
                        value="Y8NMvvT3LR7_0FE4QlfcxYPKc6Y2OK44IrCGNdMqbagnjTjstcRneKWCP3EvEIap9vpLxXJbx1sS4-9C6ksc4w==">
                    <div class="mb-3 field-feedback-fio required">
                        <label class="form-label" for="feedback-fio">Введите текущий пароль </label>
                        <input type="password" id="app-current-password" class="form-control" name="currentPassword" value="" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 field-feedback-fio required">
                        <label class="form-label" for="feedback-fio">Введите новый пароль</label>
                        <input type="password" id="app-new-password" class="form-control" name="newPassword" value="" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 field-feedback-fio required">
                        <label class="form-label" for="feedback-fio">Подтветрдите новый пароль</label>
                        <input type="text" id="app-retype-password" class="form-control" name="retypePassword" value=""  aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>

                  

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">изменить пароль</button>
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