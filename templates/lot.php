<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="/cat_lots.php?cat_id=<?=esc($val['id']); ?>"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=esc($lot['title']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=esc($lot['picture']); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=esc($lot['name']); ?></span></p>
            <p class="lot-item__description"><?=esc($lot['description'])?></p>
        </div>
        <div class="lot-item__right">
            <?php   if (!count($rate_add)): ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?=check_time_end($lot['dt_end']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=$lot['price']; ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=($lot['price'] + $lot['rate_step']); ?></span>
                        </div>
                    </div>

                    <form class="lot-item__form" action="/add_rate.php" method="post">
                        <?php $classname = isset($errors['cost']) ? "form__item--invalid" : "";
                        $value = isset($new_rate['cost']) ? esc($new_rate['cost']) : ($lot['price'] + $lot['rate_step']); ?>
                        <p class="lot-item__form-item form__item <?=$classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="new_rate[cost]" placeholder="от <?=($lot['price'] + $lot['rate_step']); ?>">
                            <input type="hidden" name="new_rate[lot_id]" value="<?=$lot['id']; ?>">
                            <span class="form__error"><?=$errors['cost'] ?? ""; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?=count($rates); ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($rates as $val): ?>
                        <tr class="history__item">
                            <td class="history__name"><?=esc($val['name']); ?></td>
                            <td class="history__price"><?=esc($val['rate_sum']); ?></td>
                            <td class="history__time"><?=esc($val['dt_add']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

</section>
