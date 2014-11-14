-- Adminer 4.1.0 SQLite 3 dump

DROP TABLE IF EXISTS "logging";
CREATE TABLE "logging" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "timestamp" integer NOT NULL,
  "filename" integer NULL DEFAULT 'inline',
  "data" text NOT NULL
);


DROP TABLE IF EXISTS "source";
CREATE TABLE "source" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "uniqid" text NOT NULL,
  "md5" text NOT NULL,
  "submitted" integer NOT NULL,
  "user_id" integer NOT NULL,
  "filename" text NULL DEFAULT 'inline',
  "content" text NOT NULL,
  "source_type" integer NOT NULL,
  FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  FOREIGN KEY ("source_type") REFERENCES "source_type" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION
);


DROP TABLE IF EXISTS "source_link";
CREATE TABLE "source_link" (
  "html_id" integer NOT NULL,
  "source_id" integer NOT NULL,
  FOREIGN KEY ("html_id") REFERENCES "source" ("id") ON DELETE CASCADE,
  FOREIGN KEY ("source_id") REFERENCES "source" ("id")
);


DROP TABLE IF EXISTS "source_settings";
CREATE TABLE "source_settings" (
  "source_id" integer NOT NULL,
  "asset_compression" integer NOT NULL DEFAULT '0',
  "script_dir" text NULL,
  "style_dir" text NULL,
  "output_options" text NOT NULL,
  "injectassets" integer NOT NULL DEFAULT '0',
  FOREIGN KEY ("source_id") REFERENCES "source" ("id") ON DELETE CASCADE ON UPDATE NO ACTION
);


DROP TABLE IF EXISTS "source_transform";
CREATE TABLE "source_transform" (
  "html_id" integer NOT NULL,
  "transform_id" integer NOT NULL,
  FOREIGN KEY ("html_id") REFERENCES "source" ("id") ON DELETE CASCADE,
  FOREIGN KEY ("transform_id") REFERENCES "source" ("id") ON DELETE CASCADE
);


DROP TABLE IF EXISTS "source_type";
CREATE TABLE "source_type" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "type" text NOT NULL
);

INSERT INTO "source_type" ("id", "type") VALUES (1, 'text/html');
INSERT INTO "source_type" ("id", "type") VALUES (2, 'text/css');
INSERT INTO "source_type" ("id", "type") VALUES (3, 'text/javascript');
INSERT INTO "source_type" ("id", "type") VALUES (4, 'application/x-javascript');
INSERT INTO "source_type" ("id", "type") VALUES (5, 'application/javascript');
INSERT INTO "source_type" ("id", "type") VALUES (6, 'text/plain');
INSERT INTO "source_type" ("id", "type") VALUES (7, 'text/xsl');
INSERT INTO "source_type" ("id", "type") VALUES (8, 'text/xml');
INSERT INTO "source_type" ("id", "type") VALUES (9, 'application/xml');

DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "username" text NOT NULL,
  "password" text NOT NULL
);

INSERT INTO "users" ("id", "username", "password") VALUES (1, 'anonymous',  '');

-- 