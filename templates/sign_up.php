<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="/index.php"><?=esc($val['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php $classname_form = isset($errors) ? "form--invalid" : ""; ?>
<form class="form container <?=$classname_form;?>" action="/signup.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";
    $value = isset($new_user['email']) ? esc($new_user['email']) : ""; ?>
    <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="email" name="new_user[email]" placeholder="Введите e-mail" value="<?=$value; ?>" maxlength="128" required>
        <span class="form__error"><?=$errors['email'] ?? ""; ?></span>
    </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : "";
    $value = isset($new_user['password']) ? esc($new_user['password']) : ""; ?>
    <div class="form__item <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="new_user[password]" placeholder="Введите пароль"  value="<?=$value; ?>" required>
        <span class="form__error"><?=$errors['password'] ?? ""; ?></span>
    </div>
    <?php $classname = isset($errors['user_name']) ? "form__item--invalid" : "";
    $value = isset($new_user['user_name']) ? esc($new_user['user_name']) : ""; ?>
    <div class="form__item <?=$classname;?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="new_user[user_name]"  placeholder="Введите имя" value="<?=$value; ?>" maxlength="128" required>
        <span class="form__error"><?=$errors['user_name'] ?? ""; ?></span>
    </div>
    <?php $classname = isset($errors['contacts']) ? "form__item--invalid" : "";
    $value = isset($new_user['contacts']) ? esc($new_user['contacts']) : ""; ?>
    <div class="form__item <?=$classname;?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="new_user[contacts]" placeholder="Напишите как с вами связаться" maxlength="255" required><?=$value; ?></textarea>
        <span class="form__error"><?=$errors['contacts'] ?? ""; ?></span>
    </div>
    <?php $classname = isset($errors['avatar']) ? "form__item--invalid" : ""; ?>
    <div class="form__item form__item--file form__item--last  <?=$classname;?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <span class="form__error"><?=$errors['avatar'] ?? ""; ?></span>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="jpg_img" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>
