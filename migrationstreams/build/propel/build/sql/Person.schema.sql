
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- persons
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `persons`;

CREATE TABLE `persons`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `birthday` DATE NOT NULL,
    `day_of_death` DATE,
    `denomination_id` INTEGER NOT NULL,
    `professional_category_id` INTEGER NOT NULL,
    `profession` VARCHAR(255) NOT NULL,
    `country_of_birth_id` INTEGER NOT NULL,
    `place_of_birth` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `persons_FI_1` (`denomination_id`),
    INDEX `persons_FI_2` (`professional_category_id`),
    INDEX `persons_FI_3` (`country_of_birth_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
