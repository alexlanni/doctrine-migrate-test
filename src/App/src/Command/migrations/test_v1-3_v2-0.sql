CREATE TABLE old_users SELECT * FROM users;

DROP TABLE users;

CREATE TABLE users(
                      id int(11) not null auto_increment,
                      username varchar(100) not null,
                      first_name varchar(50) not null,
                      last_name varchar(50) not null,
                      primary key (id)
);

INSERT INTO users (id, username, first_name, last_name)
SELECT id, username, 'xxx', 'yyy'
FROM old_users;

DROP TABLE old_users;


-- INSERT IN DB_MIGRATION
INSERT INTO db_migration
VALUES (
           now(),
           'v1.3',
           'v2.0',
           'test_v1-3_v2-0.sql'
       );