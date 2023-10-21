<?php include __DIR__ . '/../header.php' ?>

<header class="header d-flex align-items-center">
    <div class="col">
        <h1 class="title login-title">Вход</h1>
    </div>
</header>

<div class="login-main">
    <?php if(!empty($error)) : ?>
        <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
    <?php endif; ?>
    <div class="back-to-main-link">
        <a href="/">Вернуться на главную</a>
    </div>
    <form action="" method="POST" class="login-form">
        <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" aria-describedby="email" value="<?= $_POST['email'] ?? '' ?>" placeholder="Электронная почта">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" id="password" value="<?= $_POST['password'] ?? '' ?>" placeholder="Пароль">
        </div>
        <div class="login-button">
            <button type="submit" class="btn btn-success">Войти</button>
        </div>
    </form>
    <div class="already-registered">
        <p>Вы еще не зарегистрированы? <a href="register">Зарегистрироваться</a></p>
    </div>
</div>

<?php include __DIR__ . '/../footer.php' ?>