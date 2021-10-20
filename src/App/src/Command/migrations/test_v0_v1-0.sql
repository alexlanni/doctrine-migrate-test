CREATE TABLE db_migration(
                        last_update datetime not null,
                        from_version varchar(6) not null ,
                        to_version varchar(6) not null,
                        migration_script varchar(100) not null,
                        primary key (last_update)
);

CREATE TABLE testtbl(
    id int(11) not null auto_increment,
    name varchar(20) not null,
    primary key (id)
);

CREATE TABLE category(
                        id int(11) not null auto_increment,
                        label varchar(20) not null,
                        primary key (id)
);


-- INSERT IN DB_MIGRATION
INSERT INTO db_migration
VALUES (
        now(),
        'v0',
        'v1.0',
        'test_v0_v1-0.sql'
       );