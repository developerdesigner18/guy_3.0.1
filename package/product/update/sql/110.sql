CREATE TABLE `websites_heatmaps` (
`heatmap_id` int(11) NOT NULL AUTO_INCREMENT,
`website_id` int(11) NOT NULL,
`snapshot_id_desktop` int(11) DEFAULT NULL,
`snapshot_id_tablet` int(11) DEFAULT NULL,
`snapshot_id_mobile` int(11) DEFAULT NULL,
`name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`path` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`is_enabled` tinyint(4) NOT NULL DEFAULT '1',
`date` datetime NOT NULL,
PRIMARY KEY (`heatmap_id`),
KEY `website_id` (`website_id`),
KEY `snapshot_id_desktop` (`snapshot_id_desktop`),
KEY `snapshot_id_tablet` (`snapshot_id_tablet`),
KEY `snapshot_id_mobile` (`snapshot_id_mobile`),
CONSTRAINT `websites_heatmaps_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `heatmaps_snapshots` (
`snapshot_id` int(11) NOT NULL AUTO_INCREMENT,
`heatmap_id` int(11) NOT NULL,
`website_id` int(11) NOT NULL,
`type` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`data` longblob NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`snapshot_id`),
KEY `heatmap_id` (`heatmap_id`),
KEY `website_id` (`website_id`),
KEY `type` (`type`),
CONSTRAINT `heatmaps_snapshots_ibfk_1` FOREIGN KEY (`heatmap_id`) REFERENCES `websites_heatmaps` (`heatmap_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `heatmaps_snapshots_ibfk_2` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table events_children add snapshot_id int null after visitor_id;

-- SEPARATOR --

create index events_children_snapshot_id_index on events_children (snapshot_id);

-- SEPARATOR --

alter table events_children add constraint events_children_heatmaps_snapshots_snapshot_id_fk foreign key (snapshot_id) references heatmaps_snapshots (snapshot_id) on update cascade on delete set null;

-- SEPARATOR --

alter table websites_heatmaps add CONSTRAINT `websites_heatmaps_ibfk_2` FOREIGN KEY (`snapshot_id_desktop`) REFERENCES `heatmaps_snapshots` (`snapshot_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- SEPARATOR --

alter table websites_heatmaps add  CONSTRAINT `websites_heatmaps_ibfk_3` FOREIGN KEY (`snapshot_id_tablet`) REFERENCES `heatmaps_snapshots` (`snapshot_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- SEPARATOR --

alter table websites_heatmaps add  CONSTRAINT `websites_heatmaps_ibfk_4` FOREIGN KEY (`snapshot_id_mobile`) REFERENCES `heatmaps_snapshots` (`snapshot_id`) ON DELETE SET NULL ON UPDATE CASCADE;
