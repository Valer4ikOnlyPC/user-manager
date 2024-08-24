CREATE TABLE users
(
    id                       BINARY(16)   NOT NULL PRIMARY KEY,
    login                    VARCHAR(256) NOT NULL UNIQUE,
    password                 VARCHAR(256) NOT NULL,
    name_first               VARCHAR(256) NOT NULL,
    name_second              VARCHAR(256) NOT NULL,
    name_last                VARCHAR(256) NULL,
    update_date              DATETIME     NOT NULL,
    is_admin                 tinyint(1)   NOT NULL DEFAULT 0
);
