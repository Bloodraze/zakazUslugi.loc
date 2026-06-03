<?php include 'src/header.php' ?>
<?php require 'src/initLogin.php' ?>

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

                <div class="row">
                    <div class="col-lg-5">
                        <form id="login-form" action="login.php" method="post">
                            <input type="hidden" name="_csrf"
                                value="HXvJ_hFckZo_U4OGA9wD3wPlDEXQOLdkccRmUxtkS7BZNb2sUG_b_GUB7c9um1mzdtU0JpRb3gdBlw8kIgU6-w==">
                            <div class="mb-3 field-loginform-login required">
                                <label class="col-lg-1 col-form-label mr-lg-3" for="loginform-login">логин</label>
                                <input type="text" id="loginform-login" class="col-lg-3 form-control"
                                    name="LoginForm[login]" autofocus aria-required="true">
                                <div class="col-lg-7 invalid-feedback"></div>
                            </div>
                            <div class="mb-3 field-loginform-password required">
                                <label class="col-lg-1 col-form-label mr-lg-3" for="loginform-password">пароль</label>
                                <input type="password" id="loginform-password" class="col-lg-3 form-control"
                                    name="LoginForm[password]" value="" aria-required="true">
                                <div class="col-lg-7 invalid-feedback"></div>
                            </div>
                            <div class="mb-3 field-loginform-rememberme">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="LoginForm[rememberMe]" value="0" /><input type="checkbox"
                                        id="loginform-rememberme" class="form-check-input" name="LoginForm[rememberMe]"
                                        value="1" checked>
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
                        <!-- <div style="color: #999">
                            You may login with <strong>office-manager/manager123</strong> or
                            <strong>asd/asd</strong>.
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted"></div>
        </div>
    </footer>


    <script src="js/bootstrap.bundle.js"></script>

</body>

</html>