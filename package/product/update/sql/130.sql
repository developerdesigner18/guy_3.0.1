INSERT INTO `settings` (`key`, `value`) VALUES ('product_info', '');

-- SEPARATOR --

UPDATE `settings` SET `value` = '{\"version\":\"1.3.0\", \"code\":\"130\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

CREATE TABLE `websites_goals` (
`goal_id` int(11) NOT NULL AUTO_INCREMENT,
`website_id` int(11) NOT NULL,
`key` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`type` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`path` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`goal_id`),
KEY `website_id` (`website_id`),
KEY `key` (`key`),
CONSTRAINT `websites_goals_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `goals_conversions` (
`conversion_id` int(11) NOT NULL AUTO_INCREMENT,
`event_id` int(11) NOT NULL,
`session_id` int(11) NOT NULL,
`visitor_id` int(11) NOT NULL,
`goal_id` int(11) NOT NULL,
`website_id` int(11) NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`conversion_id`),
KEY `event_id` (`event_id`),
KEY `session_id` (`session_id`),
KEY `visitor_id` (`visitor_id`),
KEY `goal_id` (`goal_id`),
KEY `website_id` (`website_id`),
KEY `date` (`date`),
CONSTRAINT `goals_conversions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `sessions_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `visitors_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_3` FOREIGN KEY (`visitor_id`) REFERENCES `websites_visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_4` FOREIGN KEY (`goal_id`) REFERENCES `websites_goals` (`goal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `goals_conversions_ibfk_5` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table websites add email_reports_is_enabled tinyint default 0 null after is_enabled;

-- SEPARATOR --

alter table websites add email_reports_last_date datetime null after email_reports_is_enabled;

-- SEPARATOR --

UPDATE websites set email_reports_last_date = NOW();

-- SEPARATOR --

CREATE TABLE `email_reports` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`website_id` int(11) NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `website_id` (`website_id`),
KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
