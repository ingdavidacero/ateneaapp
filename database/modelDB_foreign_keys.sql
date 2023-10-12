-- MySQL Workbench Synchronization
-- Generated: 2023-10-07 09:18
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: DAVID

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

ALTER TABLE `ateneaapp`.`questions` 
ADD INDEX `fk_questions_categories_idx` (`categories_id` ASC),
ADD INDEX `fk_questions_levels1_idx` (`levels_id` ASC);

ALTER TABLE `ateneaapp`.`options` 
ADD INDEX `fk_options_questions1_idx` (`questions_id` ASC);

ALTER TABLE `ateneaapp`.`users` 
ADD INDEX `fk_users_entities1_idx` (`entities_id` ASC);

ALTER TABLE `ateneaapp`.`matches` 
ADD INDEX `fk_matches_match_states1_idx` (`match_states_id` ASC),
ADD INDEX `fk_matches_users1_idx` (`users_id` ASC);

ALTER TABLE `ateneaapp`.`matches_answers` 
ADD INDEX `fk_matches_questions_questions1_idx` (`questions_id` ASC),
ADD INDEX `fk_matches_questions_options1_idx` (`options_id` ASC),
ADD INDEX `fk_matches_questions_matches1_idx` (`matches_id` ASC),
ADD INDEX `fk_matches_questions_wildcards1_idx` (`wildcards_id` ASC);

ALTER TABLE `ateneaapp`.`users_token` 
ADD INDEX `fk_users_token_users1_idx` (`users_id` ASC),
ADD INDEX `fk_users_token_token_states1_idx` (`token_states_id` ASC);

ALTER TABLE `ateneaapp`.`questions` 
ADD CONSTRAINT `fk_questions_categories`
  FOREIGN KEY (`categories_id`)
  REFERENCES `ateneaapp`.`categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_questions_levels1`
  FOREIGN KEY (`levels_id`)
  REFERENCES `ateneaapp`.`levels` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `ateneaapp`.`options` 
ADD CONSTRAINT `fk_options_questions1`
  FOREIGN KEY (`questions_id`)
  REFERENCES `ateneaapp`.`questions` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `ateneaapp`.`users` 
ADD CONSTRAINT `fk_users_entities1`
  FOREIGN KEY (`entities_id`)
  REFERENCES `ateneaapp`.`entities` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `ateneaapp`.`matches` 
ADD CONSTRAINT `fk_matches_match_states1`
  FOREIGN KEY (`match_states_id`)
  REFERENCES `ateneaapp`.`match_states` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_matches_users1`
  FOREIGN KEY (`users_id`)
  REFERENCES `ateneaapp`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `ateneaapp`.`matches_answers` 
ADD CONSTRAINT `fk_matches_questions_questions1`
  FOREIGN KEY (`questions_id`)
  REFERENCES `ateneaapp`.`questions` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_matches_questions_options1`
  FOREIGN KEY (`options_id`)
  REFERENCES `ateneaapp`.`options` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_matches_questions_matches1`
  FOREIGN KEY (`matches_id`)
  REFERENCES `ateneaapp`.`matches` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_matches_questions_wildcards1`
  FOREIGN KEY (`wildcards_id`)
  REFERENCES `ateneaapp`.`wildcards` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `ateneaapp`.`users_token` 
ADD CONSTRAINT `fk_users_token_users1`
  FOREIGN KEY (`users_id`)
  REFERENCES `ateneaapp`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_users_token_token_states1`
  FOREIGN KEY (`token_states_id`)
  REFERENCES `ateneaapp`.`token_states` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
