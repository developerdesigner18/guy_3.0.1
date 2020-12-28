UPDATE `settings` SET `value` = '{\"version\":\"1.4.0\", \"code\":\"140\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users modify country varchar(32) default null;

-- SEPARATOR --

UPDATE websites_visitors SET last_date = date WHERE last_date IS NULL;

-- SEPARATOR --

alter table plans modify monthly_price float null;

-- SEPARATOR --

alter table plans modify annual_price float null;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}');

-- SEPARATOR --

alter table plans add lifetime_price float null after annual_price;

-- EXTENDED SEPARATOR --

alter table payments add status tinyint default 1 null after currency;

-- SEPARATOR --

alter table payments add payment_proof varchar(40) default null after currency;

-- SEPARATOR --

UPDATE payments SET type = 'one_time' WHERE type = 'one-time';

