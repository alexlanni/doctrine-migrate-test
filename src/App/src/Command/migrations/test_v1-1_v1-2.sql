ALTER TABLE testtbl ADD COLUMN lastupdate datetime null;

ALTER TABLE category ADD COLUMN lastupdate datetime null;


-- INSERT IN DB_MIGRATION
INSERT INTO db_migration
VALUES (
           now(),
           'v1.1',
           'v1.2',
           'test_v1-1_v1-2.sql'
       );