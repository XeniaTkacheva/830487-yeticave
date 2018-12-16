<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<!--https://echo.htmlacademy.ru-->
<?php $classname_form = isset($errors) ? "form--invalid" : ""; ?>
<form class="form container <?=$classname_form;?>" action="/login.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";
    $value = isset($old_user['email']) ? esc($old_user['email']) : ""; ?>
    <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="email" name="old_user[email]" placeholder="Введите e-mail" value="<?=$value; ?>" required>
        <span class="form__error"><?=$errors['email']; ?></span>
    </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : "";
    $value = isset($old_user['password']) ? esc($old_user['password']) : ""; ?>
    <div class="form__item form__item--last <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="old_user[password]" placeholder="Введите пароль" value="<?=$value; ?>" required>
        <span class="form__error"><?=$errors['password']; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
