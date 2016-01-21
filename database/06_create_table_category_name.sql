CREATE TABLE `official`.`category_name` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `category_id` INT(11)      NOT NULL,
  `lang_id`     INT(11)      NOT NULL DEFAULT 1,
  `name`        VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `name_idx` (`name`)
)
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8;
