<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php $classname_form = isset($errors) ? "form--invalid" : ""; ?>
<form class="form form--add-lot container <?=$classname_form; ?>" action="../add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['title']) ? "form__item--invalid" : "";
        $value = isset($for_sale['title']) ? esc($for_sale['title']) : ""; ?>
        <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="for_sale[title]" placeholder="Введите наименование лота" value="<?=$value; ?>" required>
            <span class="form__error"><?=$errors['title']; ?></span>
        </div>
        <?php $classname = isset($errors['category']) ? "form__item--invalid" : ""; ?>
        <div class="form__item">
            <label for="category">Категория</label>
            <select id="category" name="for_sale[category]" required>
                <?php foreach ($categories as $val): ?>
                    <option><?=esc($val['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$errors['category']; ?></span>
        </div>
    </div>
    <?php $classname = isset($errors['description']) ? "form__item--invalid" : "";
    $value = isset($for_sale['description']) ? esc($for_sale['description']) : ""; ?>
    <div class="form__item form__item--wide <?=$classname;?>">
        <label for="message">Описание</label>
        <textarea id="message" name="for_sale[description]" placeholder="Напишите описание лота" required><?=$value; ?></textarea>
        <span class="form__error"><?=$errors['description']; ?></span>
    </div>
    <?php $classname = isset($errors['picture']) ? "form__item--invalid" : ""; ?>
    <div class="form__item form__item--file <?=$classname; ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <span class="form__error"><?=$errors['picture']; ?></span>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="jpg_img" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <?php $classname = isset($errors['price_start']) ? "form__item--invalid" : "";
        $value = isset($for_sale['price_start']) ? esc($for_sale['price_start']) : ""; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="for_sale[price_start]" value="<?=$value; ?>" placeholder="0" required>
            <span class="form__error"><?=$errors['price_start']; ?></span>
        </div>
        <?php $classname = isset($errors['step']) ? "form__item--invalid" : "";
        $value = isset($for_sale['step']) ? esc($for_sale['step']) : ""; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="for_sale[step]" value="<?=$value; ?>" placeholder="0" required>
            <span class="form__error"><?=$errors['step']; ?></span>
        </div>
        <?php $classname = isset($errors['dt_end']) ? "form__item--invalid" : "";
        $value = isset($for_sale['dt_end']) ? esc($for_sale['dt_end']) : ""; ?>
        <div class="form__item <?=$classname;?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date"  name="for_sale[dt_end]" value="<?=$value; ?>">
            <span class="form__error"><?=$errors['dt_end']; ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom"><?=$errors['form']; ?></span>
    <button type="submit" class="button">Добавить лот</button>
</form>
