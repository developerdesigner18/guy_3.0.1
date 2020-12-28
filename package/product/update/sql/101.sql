alter table users modify timezone varchar(32) default 'UTC' null;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` int(11) NOT NULL AUTO_INCREMENT,
`url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT '',
`icon` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table pages add pages_category_id int null after page_id;

-- SEPARATOR --

create index pages_pages_category_id_index on pages (pages_category_id);

-- SEPARATOR --

create index pages_url_index on pages (url);

-- SEPARATOR --

alter table pages add constraint pages_pages_categories_pages_category_id_fk foreign key (pages_category_id) references pages_categories (pages_category_id) on update cascade on delete cascade;

-- SEPARATOR --

alter table pages add `order` int default 0 null;

-- SEPARATOR --

alter table pages add total_views int default 0 null;

-- SEPARATOR --

alter table pages modify title varchar(64) default '' not null after url;

-- SEPARATOR --

alter table pages change description content text null;

-- SEPARATOR --

alter table pages add description varchar(128) null after title;

-- SEPARATOR --

alter table pages add date datetime null;

-- SEPARATOR --

alter table pages add last_date datetime null;

-- SEPARATOR --

update pages set date = now(), last_date = now();
