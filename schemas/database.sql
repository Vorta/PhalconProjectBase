CREATE TABLE `phalcon_project`.`group` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `roles` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;

CREATE TABLE `phalcon_project`.`user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(32) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `group_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `group_id_fk_idx` (`group_id` ASC),
  CONSTRAINT `group_id_fk`
    FOREIGN KEY (`group_id`)
    REFERENCES `phalcon_project`.`group` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_520_ci;

INSERT INTO `phalcon_project`.`group` (`name`, `roles`) VALUES ('Administrators', 'ADMIN');
