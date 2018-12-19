<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $val): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="/cat_lots.php?cat_id=<?=esc($val['id']); ?>"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach($lots as $val): ?>
            <?= include_template('_lot.php', ['val' => $val]); ?>
        <?php endforeach; ?>
    </ul>
</section>
