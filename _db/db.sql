-- MySQL Script generated by MySQL Workbench
-- 02/14/17 10:58:33
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema lingo
-- -----------------------------------------------------
-- 
-- 

-- -----------------------------------------------------
-- Schema lingo
--
-- 
-- 
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `lingo` DEFAULT CHARACTER SET utf8 ;
USE `lingo` ;

-- -----------------------------------------------------
-- Table `lingo`.`leaderboard`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lingo`.`leaderboard` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `highscore` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `user_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lingo`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lingo`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` CHAR(80) NOT NULL,
  `create_time` DATETIME(6) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `id`
    FOREIGN KEY (`id`)
    REFERENCES `lingo`.`leaderboard` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lingo`.`wordlist`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lingo`.`wordlist` (
  `id` INT(11) NOT NULL,
  `words` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
