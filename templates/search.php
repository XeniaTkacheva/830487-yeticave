
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="/index.php"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=$search; ?></span>»</h2>
        <?php if ($lots): ?>
            <ul class="lots__list">
                <?php foreach($lots as $val): ?>
                    <?=include_template('_lot.php', ['val' => $val]); ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>По вашему запросу ничего не найдено</p>
        <?php endif; ?>
    </section>
</div>
