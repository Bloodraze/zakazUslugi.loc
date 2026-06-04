            </div>
        </main><footer id="footer" class="mt-auto py-3 bg-light border-top">
            <div class="container">
                <div class="row text-muted">
                    <div class="col-md-6 text-center text-md-start">
                        &copy; <?= date('Y') ?> My application. Все права защищены.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <?php $role = $_SESSION['role'] ?? ''; ?>
                        <?php if ($role === 'admin'): ?>
                            <a href="admin-panel.php">Заявки</a> |
                            <a href="admin-reviews.php">Модерация отзывов</a> |
                            <a href="register.php">Регистрация клиентов</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </footer>

        <script src="js/bootstrap.bundle.js"></script>

    </body>
</html>