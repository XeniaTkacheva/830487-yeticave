<li class="lots__item lot">
    <div class="lot__image">
        <a href="lot.php?id=<?=esc($val['id']); ?>">
            <img src="<?=esc($val['image_url']); ?>" width="350" height="260" alt="">
        </a>
    </div>
    <div class="lot__info">
        <span class="lot__category"><?=esc($val['category'])?></span>
        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=esc($val['id']); ?>"><?=esc($val['title']); ?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?php echo format_price(esc($val['price'])); ?></span>
            </div>
            <?php (strtotime($val['dt_end']) <= strtotime('tomorrow')) ? $classname = "timer--finishing" : $classname = ""; ?>
            <div class="lot__timer timer <?=$classname; ?>">
                <?=check_time_end($val['dt_end']); ?>
            </div>
        </div>
    </div>
</li>
