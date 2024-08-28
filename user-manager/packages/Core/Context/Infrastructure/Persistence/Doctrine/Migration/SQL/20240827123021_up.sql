CREATE TABLE images
(
    id                       BINARY(16)   NOT NULL PRIMARY KEY,
    dir                      VARCHAR(256) NOT NULL UNIQUE,
    web_dir                  VARCHAR(256) NOT NULL UNIQUE
);

CREATE TABLE images_users (
    image_id binary(16) not null,
    user_id binary(16) not null,
    unique index UNIQ_39BB4455DE10E562 (image_id),
    primary key (image_id, user_id)
);

ALTER TABLE images_users
    ADD CONSTRAINT FK_39BB44553DA5256D FOREIGN KEY (image_id) REFERENCES images (id);

ALTER TABLE images_users
    add CONSTRAINT FK_39BB4455A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
