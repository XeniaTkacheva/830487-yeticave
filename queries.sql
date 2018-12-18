// Добавляем категории товаров

INSERT INTO categories
  (id, name)
VALUES
  (1, 'Доски и лыжи'),
  (2, 'Крепления'),
  (3, 'Ботинки'),
  (4, 'Одежда'),
  (5, 'Инструменты'),
  (6, 'Разное');

// Добавляем пользователей

INSERT INTO users
  SET email = '3363@gmail.com', name = 'Ася Петрова', password = '123456', contacts = 'Москва, тел.+7-559-555-88-99';

INSERT INTO users
  SET email = 'petr156@rambler.ru', name = 'Петр Николаевич', password = '654321', contacts = 'деревня Дальняя, дом 3';

INSERT INTO users
  SET email = 'ququ@gmail.ru', name = 'Наташенька', password = '111222', contacts = 'Париж, рядом с башней. тел. серый';

// Добавляем объявления

INSERT INTO lots
  (name, cat_id, price_start, picture, dt_end, rate_step, user_id)
VALUES
  ('2014 Rossignol District Snowboard', 1, 10999, 'img/lot-1.jpg', '2018-12-30 20:00:00', 500, 2),
  ('DC Ply Mens 2016/2017 Snowboard', 1, 159999, 'img/lot-2.jpg', '2018-12-21 15:00:00', 10000, 1),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 'img/lot-3.jpg', '2018-12-21 15:00:00', 1000, 3),
  ('Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 'img/lot-4.jpg', '2018-12-21 15:00:00', 1500, 3),
  ('Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 'img/lot-5.jpg', '2018-12-21 15:00:00', 500, 3),
  ('Маска Oakley Canopy', 6, 5400, 'img/lot-6.jpg', '2018-12-01 18:00:00', 300, 1);


// Добавляем ставки

INSERT INTO rates
  (rate_sum, user_id, lot_id)
VALUES
  (12000, 3, 1),
  (8000, 1, 5),
  (13000, 1, 1),
  (179999, 3, 2);

// Запрос: получить все категории

SELECT * FROM categories;

// Запрос: получить самые новые, открытые лоты

SELECT DISTINCT l.id, l.name, price_start, picture, c.name, MAX(IF(rate_sum IS NULL, price_start, rate_sum)) AS price, COUNT(lot_id) AS rates_number
FROM lots l
JOIN categories c ON l.cat_id = c.id
LEFT JOIN rates r ON l.id = r.lot_id
WHERE dt_end > CURRENT_TIMESTAMP and winner_id IS NULL
GROUP BY l.id, l.name, price_start, picture, c.name
ORDER BY l.id DESC;

// Показать лот по его id

SELECT l.dt_add, l.name, cat_id, c.name, picture, price_start, dt_end, rate_step, MAX(IF(rate_sum IS NULL, l.price_start, rate_sum)) AS price, l.user_id, u.email AS 'продавец'
FROM lots l
JOIN categories c ON l.cat_id = c.id
JOIN rates r ON l.id = r.lot_id
JOIN users u ON u.id = l.user_id
WHERE l.id = 1
GROUP BY l.id, l.name, price_start, picture, c.name, rate_step;

// Обновление названия лота по id

UPDATE lots SET name = 'Лыжи еще не надеванные'
WHERE id = 2;

// Список свежих ставок для лота по его id

SELECT  dt_add, rate_sum, user_id, lot_id FROM rates
WHERE lot_id = 1
ORDER BY dt_add DESC;

// Добавление новой ставки

INSERT INTO rates
  SET rate_sum = 29900, user_id = 2, lot_id = 8;

// Запрос: показать все последние ставки по лоту с именами пользователей

SELECT  r.dt_add, rate_sum, user_id, lot_id, u.name FROM rates r
JOIN users u ON u.id = r.user_id
WHERE lot_id = 1
ORDER BY dt_add DESC;

// Полнотекстовый логический поисковый запрос

SELECT * FROM lots
WHERE MATCH (name, description) AGAINST('слово' IN BOOLEAN MODE);
