CREATE TABLE users(
                         id int(11) not null auto_increment,
                         username varchar(100) not null,
                         primary key (id)
);


-- INSERT IN DB_MIGRATION
INSERT INTO db_migration
VALUES (
           now(),
           'v1.2',
           'v1.3',
           'test_v1-2_v1-3.sql'
       );