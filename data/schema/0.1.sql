-- Adminer 4.1.0 SQLite 3 dump
DROP TABLE IF EXISTS "source_type";
CREATE TABLE "source_type" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "type" text NOT NULL
);

DELETE FROM "source_type";
INSERT INTO "source_type" ("id", "type") VALUES (1,	'text/html');
INSERT INTO "source_type" ("id", "type") VALUES (2,	'text/css');
INSERT INTO "source_type" ("id", "type") VALUES (3,	'text/javascript');

DROP TABLE IF EXISTS "sqlite_sequence";
CREATE TABLE sqlite_sequence(name,seq);

DELETE FROM "sqlite_sequence";
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('users',	'1');
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('source_type',	'3');
INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('source',	'2');

DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "username" text NOT NULL,
  "password" text NOT NULL
);

DELETE FROM "users";
INSERT INTO "users" ("id", "username", "password") VALUES (1,	'anonymous',	'');

DROP TABLE IF EXISTS "source";
CREATE TABLE "source" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "uniqid" text NOT NULL,
  "md5" text NOT NULL,
  "submitted" integer NOT NULL,
  "user_id" integer NOT NULL,
  "filename" text NOT NULL,
  "content" text NOT NULL,
  "source_type" integer NOT NULL,
  FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  FOREIGN KEY ("source_type") REFERENCES "source_type" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION,
);

DROP TABLE IF EXISTS "source_link";
CREATE TABLE "source_link" (
  "html_id" integer NOT NULL,
  "source_id" integer NOT NULL,
  FOREIGN KEY ("html_id") REFERENCES "source" ("id") ON DELETE CASCADE,
  FOREIGN KEY ("source_id") REFERENCES "source" ("id")
);

-- 