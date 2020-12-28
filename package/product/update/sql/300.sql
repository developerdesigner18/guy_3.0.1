UPDATE `settings` SET `value` = '{\"version\":\"3.0.0\", \"code\":\"300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table websites drop column exclude_bots;

-- SEPARATOR --

alter table websites_visitors drop column country_name;

-- SEPARATOR --

alter table websites_visitors add city_name varchar(128) null after country_code;

-- SEPARATOR --

CREATE TABLE `lightweight_events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) NOT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_host` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_source` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os_name` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser_name` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser_language` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `screen_resolution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `date_day` date DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `website_id` (`website_id`),
  KEY `date` (`date`) USING BTREE,
  KEY `date_day` (`date_day`) USING BTREE,
  CONSTRAINT `lightweight_events_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

alter table goals_conversions modify session_id int null;

-- SEPARATOR --

alter table goals_conversions modify visitor_id int null;

-- SEPARATOR --

alter table websites add tracking_type varchar(16) default 'normal' null after path;

-- SEPARATOR --

alter table goals_conversions modify event_id int null;


