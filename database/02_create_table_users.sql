CREATE TABLE `official`.`users` (
	`user_id`      INT(11)      NOT NULL AUTO_INCREMENT,
	`username`     VARCHAR(128) NOT NULL,
	`password`     VARCHAR(256) NOT NULL,
	`active`       INT(1)       NOT NULL DEFAULT 1,
	`display_name` VARCHAR(64)  NULL     DEFAULT NULL,
	`created`      DATETIME     NULL,
	`modified`     DATETIME     NULL,
	PRIMARY KEY (`user_id`),
	UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
	INDEX `username_idx` USING BTREE (`username` ASC),
	INDEX `created_idx` (`created` ASC)
)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8;
ALTER TABLE `official`.`users`
