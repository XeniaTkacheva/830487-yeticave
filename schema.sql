CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id INT(11) NOT NULL PRIMARY KEY,
  name CHAR(25) NOT NULL UNIQUE,
  sort INT(11)
);

CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password CHAR(64) NOT NULL,
  avatar CHAR(128),
  contacts CHAR(255)
);

CREATE TABLE lots (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  name CHAR(80) NOT NULL,
  description CHAR(255),
  cat_id INT(11) NOT NULL,
  picture CHAR(80) NOT NULL,
  price INT(11) NOT NULL,
  dt_end TIMESTAMP NOT NULL,
  rate_step INT(11),
  user_id INT(11) NOT NULL,
  winner_id INT(11),
  KEY lot_dt (cat_id, dt_add),
  FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

CREATE TABLE rates (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  rate_sum INT(11) NOT NULL,
  user_id INT(11) NOT NULL,
  lot_id INT(11) NOT NULL,
  FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

