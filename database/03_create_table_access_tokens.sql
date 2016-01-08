CREATE TABLE `official`.`access_tokens` (
	`token_id`  INT(11)      NOT NULL AUTO_INCREMENT,
	`user_id`   INT(11)      NOT NULL,
	`token`     TEXT         NOT NULL,
	`client_ip` VARCHAR(20)  NULL     DEFAULT NULL,
	`created`   DATETIME     NULL     DEFAULT NULL,
	`expired`   VARCHAR(100) NULL     DEFAULT NULL,

	PRIMARY KEY (`token_id`),
	UNIQUE INDEX `token_id_UNIQUE` (`token_id` ASC)
)
	DEFAULT CHARACTER SET = utf8;
