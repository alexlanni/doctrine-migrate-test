ALTER TABLE testtbl ADD COLUMN username varchar(40) not null;

-- INSERT IN DB_MIGRATION
INSERT INTO db_migration
VALUES (
           now(),
           'v1.0',
           'v1.1',
           'test_v1-0_v1-1.sql'
       );