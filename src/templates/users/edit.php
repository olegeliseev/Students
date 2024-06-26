<?php include __DIR__ . '/../header.php' ?>

<header class="header d-flex align-items-center">
    <div class="col">
        <h1 class="title registration-title">Изменение данных</h1>
    </div>
</header>

<div class="registration-main">
    <?php if(!empty($error)) : ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endif; ?>
    <div class="back-to-main-link">
        <a href="/">Вернуться на главную</a>
    </div>
    <form action="" method="POST" class="registration-form">
        <div class="mb-3">
            <input type="text" class="form-control" id="firstName" name="firstName" aria-describedby="firstName" value="<?= $_POST['firstName'] ?? $user->getFirstName() ?>" placeholder="Имя">
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="lastName" name="lastName" aria-describedby="lastName" value="<?= $_POST['lastName'] ?? $user->getLastName() ?>" placeholder="Фамилия">
        </div>
        <div class="mb-3 check-input">
            <label for="gender" class="form-label">Пол:</label>
            <div class="form-check">
                <label class="form-check-label" for="gender1">М</label>
                <?php if((!empty($_POST['gender']) && $_POST['gender'] == 'male') || $user->getGender() == 'male'): ?>
                    <input class="form-check-input" type="radio" name="gender" value='male' id="gender1" checked>
                <?php else: ?>
                    <input class="form-check-input" type="radio" name="gender" value='male' id="gender1">
                <?php endif; ?>
            </div>
            <div class="form-check">
                <label class="form-check-label" for="gender2">Ж</label>
                <?php if((!empty($_POST['gender']) && $_POST['gender'] == 'female') || $user->getGender() == 'female'): ?>
                    <input class="form-check-input" type="radio" name="gender" value='female' id="gender2" checked>
                <?php else: ?>
                    <input class="form-check-input" type="radio" name="gender" value='female' id="gender2">
                <?php endif; ?>
            </div>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="groupNumber" name="groupNumber" aria-describedby="groupNumber" value="<?= $_POST['groupNumber'] ?? $user->getGroupNumber() ?>" placeholder="Номер группы">
        </div>
        <div class="mb-3">
            <input type="number" min="1900" max="2050" step="1" class="form-control" name="birthYear" id="birthYear" value="<?= $_POST['birthYear'] ?? $user->getBirthYear() ?>" placeholder="Год рождения">
        </div>
        <div class="mb-3">
            <input type="number" min="0" max="300" step="1" class="form-control" name="points" id="points" value="<?= $_POST['points'] ?? $user->getPoints() ?>" placeholder="Суммарное число баллов ЕГЭ">
        </div>
        <div class="mb-3 check-input">
            <label for="residence" class="form-label">Проживание:</label>
            <div class="form-check">
                <label class="form-check-label" for="residence1">Местный</label>
                <?php if((!empty($_POST['residence']) && $_POST['residence'] == 'resident') || $user->getResidence() == 'resident'): ?>
                    <input class="form-check-input" type="radio" name="residence" value='resident' id="residence1" checked>
                <?php else: ?>
                    <input class="form-check-input" type="radio" name="residence" value='resident' id="residence1">
                <?php endif; ?>
            </div>
            <div class="form-check">
                <label class="form-check-label" for="residence2">Иногородный</label>
                <?php if((!empty($_POST['residence']) && $_POST['residence'] == 'nonresident') || $user->getResidence() == 'nonresident'): ?>
                    <input class="form-check-input" type="radio" name="residence" value='nonresident' id="residence2" checked>
                <?php else: ?>
                    <input class="form-check-input" type="radio" name="residence" value='nonresident' id="residence2">
                <?php endif; ?>
            </div>
        </div>
        <div class="registration-button">
            <button type="submit" class="btn btn-success">Отправить</button>
        </div>
    </form>
    <div class="already-registered">
        <p>Вы уже зарегистрированы? <a href="login">Войти</a></p>
    </div>
</div>

<?php include __DIR__ . '/../footer.php' ?>