CREATE TABLE `official`.`category_seo_link` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `category_id` INT(11)      NOT NULL,
  `lang_id`     INT(11)      NOT NULL DEFAULT 1,
  `abbr_cd`     VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `name_idx` (`abbr_cd`)
)
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8;
