<?php include __DIR__ . '/../header.php'; ?>
<div class="alert alert-danger error-banner" role="alert">
    <?= $error ?>
    <br>
    Разрешенные методы:
    <?php foreach($allowedMethods as $allowedMethod): ?>
        <?= $allowedMethod . ' ' ?>
    <?php endforeach; ?>
    <a href="/">Вернуться на главную</a>
</div>
<?php include __DIR__ . '/../footer.php'; ?>