<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="promo__item">
                <a class="promo__link" href="/cat_lots.php?cat_id=<?=esc($val['id']); ?>"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <?php if ($lots): ?>
            <h2>Все лоты в категории <span><?=esc($cat_name); ?></span></h2>
            <ul class="lots__list">
                <?php foreach($lots as $val): ?>
                    <?= include_template('_lot.php', ['val' => $val]); ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h2>Пустая категория</h2>
        <?php endif; ?>
    </section>
</div>
