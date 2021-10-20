
ALTER TABLE users DROP COLUMN first_name;
ALTER TABLE users DROP COLUMN last_name;

-- INSERT IN DB_MIGRATION
INSERT INTO db_migration
VALUES (
           now(),
           'v2.0',
           'v1.3',
           'test_v2-0_v1-3.sql'
       );