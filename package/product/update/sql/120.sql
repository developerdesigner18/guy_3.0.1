alter table users add twofa_secret varchar(16) null after token_code;

-- SEPARATOR --

alter table sessions_events add has_bounced int null after viewport_height;

-- SEPARATOR --

alter table visitors_sessions add total_events int default 1 null after website_id;

-- SEPARATOR --

DELETE FROM `sessions_replays`;

-- SEPARATOR --

alter table plans change is_enabled status tinyint not null;

-- SEPARATOR --

UPDATE settings SET value = '{\"plan_id\":\"custom\",\"name\":\"Custom\",\"status\":1}' WHERE `key` = 'plan_custom';
