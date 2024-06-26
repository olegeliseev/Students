<?php

use Students\Helpers\Util;

include __DIR__ . '/../header.php' ?>
<header class="header d-flex align-items-center">
    <div class="col-8">
        <h1 class="title">Список студентов</h1>
    </div>
    <div class="col-4">
        <form action="<?= ($pagesCount <= 1) ? "/1" : "/" ?>" 
            method="GET"
            class="d-flex justify-content-end" 
            role="search">
                <div class="search-bar col-auto">
                    <input type="hidden" name="order" value="<?=$order?>">
                    <input type="hidden" name="direction" value="<?=$direction?>">
                    <input class="form-control" name="search" value="<?= $search ?>" id="search" type="search" placeholder="Поиск" aria-label="Search">
                </div>
                <div class="col-auto"><button class="btn btn-outline-success" type="submit">Найти</button></div>
        </form>
    </div>
</header>
<content>
    <div class="links">
        <?php if (!empty($user)) : ?>
            <?= "Добро пожаловать " . $user->getFirstName() . "!" ?>
            <br>
            <a href="/users/logout">Выйти</a> |
            <?= '<a href="/users/' . $user->getId() . '/edit">Редактировать данные</a>' ?>
        <?php else : ?>
            <a href="/users/login">Войти</a> | <a href="/users/register">Зарегистрироваться</a>
        <?php endif; ?>
    </div>
    <?php if(!empty($_GET['search'])): ?>
        <div class="search-result-title">
            <p>Показаны только абитуриенты, найденные по запросу «<?= $search ?>». | <a href="/?order=<?= $order ?>&direction=<?= $direction ?>">Сбросить результаты поиска</a></p>
        </div>
    <?php endif; ?>

    <table class="table table-sm table-striped table-hover table-bordered">
        <thead class="table-warning">
            <tr>
                <th scope="col">
                    <div class="column-header">
                        <a href="/<?= $currentPageNum . '?' . Util::getSortingLink('firstName', $search) ?>">Имя</a>
                        <span><?= Util::showSortingArrow('first_name', $order, $direction) ?></span>
                    </div>
                </th>
                <th scope="col">
                    <div class="column-header">
                        <a href="/<?= $currentPageNum . '?' . Util::getSortingLink('lastName', $search) ?>">Фамилия</a>
                        <span><?= Util::showSortingArrow('last_name', $order, $direction) ?></span>
                    </div>
                </th>
                <th scope="col">
                    <div class="column-header">
                        <a href="/<?= $currentPageNum . '?' . Util::getSortingLink('groupNumber', $search) ?>">Номер группы</a>
                        <span><?= Util::showSortingArrow('group_number', $order, $direction) ?></span>
                    </div>
                </th>
                <th scope="col">
                    <div class="column-header">
                        <a href="/<?= $currentPageNum . '?' . Util::getSortingLink('points', $search) ?>">Баллы ЕГЭ</a>
                        <span><?= Util::showSortingArrow('points', $order, $direction) ?></span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php if(isset($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= Util::highlightSearchResult($student->getFirstName(), $search) ?></td>
                        <td><?= Util::highlightSearchResult($student->getLastName(), $search) ?></td>
                        <td><?= Util::highlightSearchResult($student->getGroupNumber(), $search) ?></td>
                        <td><?= Util::highlightSearchResult($student->getPoints(), $search) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php if($pagesCount > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($previousPageLink !== null) : ?>
                <li class="page-item"><a class="page-link" href="/<?= $beginningLink ?>">Начало</a></li>
                <li class="page-item page-before"><a class="page-link" href="<?= $previousPageLink ?>"><</a></li>
            <?php else : ?>
                <li class="page-item"><a class="page-link disabled" href="">Начало</a></li>
                <li class="page-item page-before"><a class="page-link disabled" href=""><</a></li>
            <?php endif; ?>

            <?php foreach ($pageLinks as $pageLink) : ?>
                <?php if ($pageLink['pageNum'] == $currentPageNum) : ?>
                    <li class="page-item page-number"><a class="page-link active" href=""><?= $pageLink['pageNum']?></a></li>
                <?php else : ?>
                    <li class="page-item page-number">
                        <a class="page-link" 
                            href="/<?= $pageLink['pageNum'] === 1 . $pageLink['url'] ? '' : $pageLink['pageNum'] . $pageLink['url']?>">
                            <?= $pageLink['pageNum']?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($nextPageLink !== null) : ?>
                <li class="page-item page-after"><a class="page-link" href="<?= $nextPageLink ?>">></a></li>
                <li class="page-item"><a class="page-link" href="/<?= $endLink ?>">Конец</a></li>
            <?php else : ?>

                <li class="page-item page-after"><a class="page-link disabled" href="">></a></li>
                <li class="page-item"><a class="page-link disabled" href="">Конец</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>

</content>
<?php include __DIR__ . '/../footer.php' ?>