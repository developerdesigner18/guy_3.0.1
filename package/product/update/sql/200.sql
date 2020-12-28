UPDATE `settings` SET `value` = '{\"version\":\"2.0.0\", \"code\":\"200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table plans add taxes_ids text null after settings;

-- SEPARATOR --

alter table users add billing text null after name;

-- SEPARATOR --

alter table users add pending_email varchar(128) null after twofa_secret;

-- SEPARATOR --

alter table websites add exclude_bots tinyint default 1 null after excluded_ips;

-- EXTENDED SEPARATOR --

alter table payments modify user_id int null;

-- SEPARATOR --

alter table payments add billing text null after name;

-- SEPARATOR --

alter table payments add taxes_ids text null after billing;

-- SEPARATOR --

alter table payments add base_amount float null after plan_id;

-- SEPARATOR --

alter table payments add discount_amount float null after code;

-- SEPARATOR --

alter table payments change amount total_amount float null;

-- SEPARATOR --

CREATE TABLE `taxes` (
`tax_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`internal_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`value` int(11) DEFAULT NULL,
`value_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`type` enum('inclusive','exclusive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`billing_type` enum('personal','business','both') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`countries` text COLLATE utf8mb4_unicode_ci,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
