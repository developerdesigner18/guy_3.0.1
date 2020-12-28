alter table users add country varchar(32) default '' not null;

-- SEPARATOR --

alter table events_children add expiration_date date null;

-- SEPARATOR --

alter table sessions_replays add expiration_date date null;

-- SEPARATOR --

CREATE INDEX expiration_date ON events_children (expiration_date);

-- SEPARATOR --

CREATE INDEX expiration_date ON sessions_replays (expiration_date);
