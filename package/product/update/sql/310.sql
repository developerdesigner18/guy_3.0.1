UPDATE `settings` SET `value` = '{\"version\":\"3.1.0\", \"code\":\"310\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

ALTER TABLE sessions_events DROP COLUMN date_day;

-- SEPARATOR --

drop index date_day on lightweight_events;

-- SEPARATOR --

alter table lightweight_events drop column date_day;

-- SEPARATOR --

drop index website_id_date_day_visitor_id on sessions_events;

-- SEPARATOR --

drop index website_id_date_day_session_id on sessions_events;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('default_theme_style', 'light');

