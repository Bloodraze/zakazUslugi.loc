<?php require 'src/initLogin.php'; ?>
<?php include 'src/header.php'; ?>

<nav aria-label="breadcrumb">
    <ol id="w2" class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            авторизация
        </li>
    </ol>
</nav>

<div class="site-login">
    <h1>Авторизация</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5">
            <form id="login-form" action="login.php" method="post">
                <div class="mb-3 field-loginform-login required">
                    <label class="col-lg-1 col-form-label mr-lg-3" for="loginform-login">логин</label>
                    <input type="text"
                           id="loginform-login"
                           class="col-lg-3 form-control"
                           name="LoginForm[login]"
                           autofocus
                           aria-required="true">
                    <div class="col-lg-7 invalid-feedback"></div>
                </div>

                <div class="mb-3 field-loginform-password required">
                    <label class="col-lg-1 col-form-label mr-lg-3" for="loginform-password">пароль</label>
                    <input type="password"
                           id="loginform-password"
                           class="col-lg-3 form-control"
                           name="LoginForm[password]"
                           value=""
                           aria-required="true">
                    <div class="col-lg-7 invalid-feedback"></div>
                </div>

                <div class="mb-3 field-loginform-rememberme">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="LoginForm[rememberMe]" value="0" />
                        <input type="checkbox"
                               id="loginform-rememberme"
                               class="form-check-input"
                               name="LoginForm[rememberMe]"
                               value="1"
                               checked>
                        <label class="form-check-label" for="loginform-rememberme">запомнить меня</label>
                    </div>
                    <div class="col-lg-8">
                        <div class="col-lg-7 invalid-feedback"></div>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-primary" name="login-button">
                            войти
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'src/footer.php'; ?>