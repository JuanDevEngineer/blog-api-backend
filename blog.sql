-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema blog_react_php
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blog_react_php` DEFAULT CHARACTER SET utf8 ;
USE `blog_react_php` ;

-- -----------------------------------------------------
-- Table `blog_react_php`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_react_php`.`categorias` (
  `id_categoria` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id_categoria`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `blog_react_php`.`blogs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_react_php`.`blogs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` INT(11) NOT NULL,
  `titulo` VARCHAR(255) NULL DEFAULT NULL,
  `slug` VARCHAR(255) NULL DEFAULT NULL,
  `texto_corto` TEXT NULL DEFAULT NULL,
  `texto_largo` LONGTEXT NULL DEFAULT NULL,
  `nombre_imagen` VARCHAR(200) NULL DEFAULT NULL,
  `fecha_creacion` DATE NULL DEFAULT NULL,
  `fecha_actualización` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_categoria_idx` (`id_categoria` ASC),
  CONSTRAINT `fk_categoria`
    FOREIGN KEY (`id_categoria`)
    REFERENCES `blog_react_php`.`categorias` (`id_categoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `blog_react_php`.`tipo_usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_react_php`.`tipo_usuarios` (
  `id_tipo` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id_tipo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;




-- -----------------------------------------------------
-- Table `blog_react_php`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_react_php`.`usuarios` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NULL DEFAULT NULL,
  `correo` VARCHAR(100) NULL DEFAULT NULL,
  `contrasena` VARCHAR(150) NULL DEFAULT NULL,
  `numero_movil` VARCHAR(100) NULL DEFAULT NULL,
  `tipo_usuario` INT(11) NOT NULL,
  `fecha_creacion` DATE NULL DEFAULT NULL,
  `fecha_actualizacion` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_tipo_usuario_idx` (`tipo_usuario` ASC),
  CONSTRAINT `fk_tipo_usuario`
    FOREIGN KEY (`tipo_usuario`)
    REFERENCES `blog_react_php`.`tipo_usuarios` (`id_tipo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

INSERT INTO `tipo_usuarios`(`id_tipo`, `nombre`) VALUES(1, 'Administrador')
INSERT INTO `tipo_usuarios`(`id_tipo`, `nombre`) VALUES(2, 'Usuario')


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
