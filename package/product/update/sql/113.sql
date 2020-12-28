LOCK TABLES sessions_replays WRITE;

-- SEPARATOR --

DELETE FROM sessions_replays;

-- SEPARATOR --

alter table sessions_replays add events int null after website_id;

-- SEPARATOR --

alter table sessions_replays drop column type;

-- SEPARATOR --

alter table sessions_replays drop column data;

-- SEPARATOR --

alter table sessions_replays add last_date datetime null;

-- SEPARATOR --

alter table sessions_replays drop column timestamp;

-- SEPARATOR --

alter table sessions_replays add constraint sessions_replays_pk unique (session_id);

-- SEPARATOR --

UNLOCK TABLES;

-- SEPARATOR --

create index visitors_sessions_date_index on visitors_sessions (date);

-- SEPARATOR --

alter table websites add excluded_ips text null after path;
