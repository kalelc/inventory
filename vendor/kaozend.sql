SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `kaozend` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `kaozend` ;

-- -----------------------------------------------------
-- Table `kaozend`.`apps`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`apps` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NULL DEFAULT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`banks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`banks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`brands`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`brands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NOT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `background_image` VARCHAR(45) NULL DEFAULT NULL COMMENT 'esta variable  o foto se va a usar para hacer el manual del producto el fondo de la vitrina ' ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`master_categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`master_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NOT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `master_category` INT(11) NOT NULL ,
  `singular_name` VARCHAR(120) NOT NULL ,
  `plural_name` VARCHAR(120) NOT NULL ,
  `image` VARCHAR(60) NULL DEFAULT NULL ,
  `shipping_cost` FLOAT NOT NULL ,
  `additional_shipping` FLOAT NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_categorias_clasificaciones1_idx` (`master_category` ASC) ,
  CONSTRAINT `fk_categorias_clasificaciones1`
    FOREIGN KEY (`master_category` )
    REFERENCES `kaozend`.`master_categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`serials_name`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`serials_name` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`categories_serials_name`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`categories_serials_name` (
  `category` INT(11) NOT NULL ,
  `serial_name` INT(11) NOT NULL ,
  PRIMARY KEY (`category`, `serial_name`) ,
  INDEX `fk_table1_categories1_idx` (`category` ASC) ,
  INDEX `fk_table1_serials_name1_idx` (`serial_name` ASC) ,
  CONSTRAINT `fk_table1_categories1`
    FOREIGN KEY (`category` )
    REFERENCES `kaozend`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_serials_name1`
    FOREIGN KEY (`serial_name` )
    REFERENCES `kaozend`.`serials_name` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`specifications_masters`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`specifications_masters` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NOT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`specifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`specifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NOT NULL ,
  `specification_master` INT(11) NOT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `meaning` LONGTEXT NULL DEFAULT NULL ,
  `general_information` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_especificaciones_especificaciones_maestras1_idx` (`specification_master` ASC) ,
  CONSTRAINT `fk_especificaciones_especificaciones_maestras1`
    FOREIGN KEY (`specification_master` )
    REFERENCES `kaozend`.`specifications_masters` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`categories_specifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`categories_specifications` (
  `category` INT(11) NOT NULL ,
  `specification` INT(11) NOT NULL ,
  `name` INT(11) NOT NULL ,
  `order` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`category`, `specification`) ,
  INDEX `fk_categories_specifications_categories1_idx` (`category` ASC) ,
  INDEX `fk_categories_specifications_specifications1_idx` (`specification` ASC) ,
  INDEX `order` (`order` ASC) ,
  CONSTRAINT `fk_categories_specifications_categories1`
    FOREIGN KEY (`category` )
    REFERENCES `kaozend`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_categories_specifications_specifications1`
    FOREIGN KEY (`specification` )
    REFERENCES `kaozend`.`specifications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`departments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`departments` (
  `id` INT(11) NOT NULL ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`cities`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`cities` (
  `id` INT(11) NOT NULL ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `department` INT(11) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cities_departments1_idx` (`department` ASC) ,
  CONSTRAINT `fk_cities_departments1`
    FOREIGN KEY (`department` )
    REFERENCES `kaozend`.`departments` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`user_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`user_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `description` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`classifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`classifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  `user_type` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_classifications_user_types1_idx` (`user_type` ASC) ,
  CONSTRAINT `fk_classifications_user_types1`
    FOREIGN KEY (`user_type` )
    REFERENCES `kaozend`.`user_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`customers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `identification` VARCHAR(20) NOT NULL ,
  `identification_type` INT(11) NOT NULL ,
  `first_name` VARCHAR(120) NOT NULL ,
  `last_name` VARCHAR(120) NOT NULL ,
  `emails` LONGTEXT NULL DEFAULT NULL ,
  `addresses` LONGTEXT NULL DEFAULT NULL ,
  `phones` LONGTEXT NULL DEFAULT NULL ,
  `zipcode` INT(11) NULL DEFAULT NULL ,
  `company` VARCHAR(120) NULL DEFAULT NULL ,
  `manager` VARCHAR(120) NULL DEFAULT NULL ,
  `webpage` VARCHAR(120) NULL DEFAULT NULL ,
  `birthday` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `alias` VARCHAR(45) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  `city` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `identification_UNIQUE` (`identification` ASC) ,
  INDEX `fk_customers_cities1_idx` (`city` ASC) ,
  CONSTRAINT `fk_customers_cities1`
    FOREIGN KEY (`city` )
    REFERENCES `kaozend`.`cities` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_authorized`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`customers_authorized` (
  `customer` INT(11) NOT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `first_name` VARCHAR(120) NOT NULL ,
  `last_name` VARCHAR(120) NOT NULL ,
  `email` VARCHAR(60) NULL DEFAULT NULL ,
  `telephone` VARCHAR(20) NULL DEFAULT NULL ,
  `status` INT(11) NOT NULL ,
  INDEX `fk_terceros_personas_autorizadas_terceros1_idx` (`customer` ASC) ,
  CONSTRAINT `fk_terceros_personas_autorizadas_terceros1`
    FOREIGN KEY (`customer` )
    REFERENCES `kaozend`.`customers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_classifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`customers_classifications` (
  `customer` INT(11) NOT NULL ,
  `classification` INT(11) NOT NULL ,
  PRIMARY KEY (`customer`, `classification`) ,
  INDEX `fk_table1_classifications1_idx` (`classification` ASC) ,
  INDEX `fk_table1_customers1_idx` (`customer` ASC) ,
  CONSTRAINT `fk_table1_classifications1`
    FOREIGN KEY (`classification` )
    REFERENCES `kaozend`.`classifications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_customers1`
    FOREIGN KEY (`customer` )
    REFERENCES `kaozend`.`customers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`dependencies`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`dependencies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  `status` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`products`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `upc_bar_code` INT(10) UNSIGNED NOT NULL ,
  `model` VARCHAR(60) NOT NULL ,
  `brand` INT(11) NOT NULL ,
  `category` INT(11) NOT NULL ,
  `part_no` VARCHAR(60) NULL DEFAULT NULL ,
  `price` FLOAT NULL DEFAULT NULL ,
  `iva` FLOAT NULL DEFAULT NULL COMMENT '0,5,10,16' ,
  `qty_low` INT(11) NULL DEFAULT NULL ,
  `qty_buy` INT(11) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  `specification_file` VARCHAR(45) NULL DEFAULT NULL ,
  `image1` VARCHAR(45) NULL DEFAULT NULL ,
  `image2` VARCHAR(45) NULL DEFAULT NULL ,
  `image3` VARCHAR(45) NULL DEFAULT NULL ,
  `image4` VARCHAR(45) NULL DEFAULT NULL ,
  `image5` VARCHAR(45) NULL DEFAULT NULL ,
  `image6` VARCHAR(45) NULL DEFAULT NULL ,
  `manual_file` VARCHAR(45) NULL DEFAULT NULL ,
  `video` LONGTEXT NULL DEFAULT NULL COMMENT 'nuevo\nusado\nremanufacturado\nnew pull' ,
  `status` INT(11) NULL DEFAULT NULL ,
  `register_date` TIMESTAMP NULL DEFAULT NULL ,
  `update_date` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_productos_marcas1_idx` (`brand` ASC) ,
  INDEX `fk_productos_categorias1` (`category` ASC) ,
  CONSTRAINT `fk_productos_categorias1`
    FOREIGN KEY (`category` )
    REFERENCES `kaozend`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_productos_marcas1`
    FOREIGN KEY (`brand` )
    REFERENCES `kaozend`.`brands` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`payments_methods`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`payments_methods` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(120) CHARACTER SET 'latin1' COLLATE 'latin1_bin' NOT NULL ,
  `description` LONGTEXT CHARACTER SET 'latin1' COLLATE 'latin1_bin' NULL DEFAULT NULL ,
  `bank_info` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_bin;


-- -----------------------------------------------------
-- Table `kaozend`.`roles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(120) NOT NULL ,
  `last_name` VARCHAR(120) NOT NULL ,
  `username` VARCHAR(100) NOT NULL ,
  `email` VARCHAR(60) NOT NULL ,
  `picture` VARCHAR(60) NULL DEFAULT NULL ,
  `signature` VARCHAR(60) NULL DEFAULT NULL ,
  `rol` INT(11) NOT NULL ,
  `password` VARCHAR(100) NOT NULL ,
  `hash` VARCHAR(100) NOT NULL ,
  `status` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) ,
  INDEX `fk_usuarios_roles1_idx` (`rol` ASC) ,
  CONSTRAINT `fk_usuarios_roles1`
    FOREIGN KEY (`rol` )
    REFERENCES `kaozend`.`roles` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1
COMMENT = 'password encriptado con md5\nfechas timestamp';


-- -----------------------------------------------------
-- Table `kaozend`.`receive_inventory`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`receive_inventory` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user` INT(11) NOT NULL ,
  `customer` INT(11) NOT NULL ,
  `payment_method` INT(11) NOT NULL ,
  `shipment` INT(11) NOT NULL ,
  `guide` VARCHAR(45) NULL DEFAULT NULL ,
  `invoice` VARCHAR(45) NULL DEFAULT NULL ,
  `invoice_file` VARCHAR(45) NULL DEFAULT NULL ,
  `observations` VARCHAR(45) NULL DEFAULT NULL ,
  `register_date` TIMESTAMP NULL DEFAULT NULL ,
  `update_date` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_receive_inventory_customers1` (`customer` ASC) ,
  INDEX `fk_receive_inventory_payments_methods1` (`payment_method` ASC) ,
  INDEX `fk_receive_inventory_customers2` (`shipment` ASC) ,
  INDEX `fk_receive_inventory_users1_idx` (`user` ASC) ,
  CONSTRAINT `fk_receive_inventory_customers1`
    FOREIGN KEY (`customer` )
    REFERENCES `kaozend`.`customers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_receive_inventory_customers2`
    FOREIGN KEY (`shipment` )
    REFERENCES `kaozend`.`customers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_receive_inventory_payments_methods1`
    FOREIGN KEY (`payment_method` )
    REFERENCES `kaozend`.`payments_methods` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_receive_inventory_users1`
    FOREIGN KEY (`user` )
    REFERENCES `kaozend`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`details_receive_inventory`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`details_receive_inventory` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `receive_inventory` INT(11) NOT NULL ,
  `cost` FLOAT NOT NULL ,
  `iva` INT(11) NULL DEFAULT NULL ,
  `product` INT(11) NOT NULL ,
  `qty` INT(11) NOT NULL ,
  `serials` LONGTEXT NOT NULL ,
  `manifest_file` VARCHAR(45) NULL DEFAULT NULL ,
  `register_date` TIMESTAMP NULL DEFAULT NULL ,
  `update_date` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_details_receive_inventory_receive_inventory1_idx` (`receive_inventory` ASC) ,
  INDEX `fk_details_receive_inventory_products1_idx` (`product` ASC) ,
  CONSTRAINT `fk_details_receive_inventory_products1`
    FOREIGN KEY (`product` )
    REFERENCES `kaozend`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_details_receive_inventory_receive_inventory1`
    FOREIGN KEY (`receive_inventory` )
    REFERENCES `kaozend`.`receive_inventory` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`list_prices`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`list_prices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NULL DEFAULT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  `principal` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`log_authenticate`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`log_authenticate` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user` INT(11) NOT NULL ,
  `error_code` VARCHAR(45) NOT NULL ,
  `values` LONGTEXT NOT NULL ,
  `register_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_logs_users1_idx` (`user` ASC) ,
  CONSTRAINT `fk_logs_users1`
    FOREIGN KEY (`user` )
    REFERENCES `kaozend`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`logs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `table` VARCHAR(100) NOT NULL ,
  `table_id` INT(11) NULL DEFAULT NULL ,
  `operation` INT(11) NOT NULL COMMENT '1,2,3,4 constantes' ,
  `user` INT(11) NOT NULL ,
  `data` LONGTEXT NULL ,
  `register_date` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_logs_users2_idx` (`user` ASC) ,
  CONSTRAINT `fk_logs_users2`
    FOREIGN KEY (`user` )
    REFERENCES `kaozend`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`measures_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`measures_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `abbreviation` VARCHAR(60) NOT NULL ,
  `name` VARCHAR(120) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`measures`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`measures` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `specification` INT(11) NOT NULL ,
  `measure_type` INT(11) NULL DEFAULT NULL ,
  `measure_value` VARCHAR(120) NOT NULL ,
  `image` VARCHAR(45) NULL DEFAULT NULL ,
  `meaning` LONGTEXT NULL DEFAULT NULL ,
  `general_information` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_table1_unidades_medidas1_idx` (`measure_type` ASC) ,
  INDEX `fk_medidas_especificaciones1_idx` (`specification` ASC) ,
  CONSTRAINT `fk_medidas_especificaciones1`
    FOREIGN KEY (`specification` )
    REFERENCES `kaozend`.`specifications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_unidades_medidas1`
    FOREIGN KEY (`measure_type` )
    REFERENCES `kaozend`.`measures_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`modules`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`modules` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `url` VARCHAR(60) NOT NULL ,
  `description` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nombre_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`modules_roles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`modules_roles` (
  `module` INT(11) NOT NULL ,
  `rol` INT(11) NOT NULL ,
  `operation` INT(11) NOT NULL COMMENT '1: consultar,2 crear,3 actualizar, 4 borrar' ,
  PRIMARY KEY (`module`, `rol`, `operation`) ,
  INDEX `fk_table1_modulos_idx` (`module` ASC) ,
  INDEX `fk_table1_roles1_idx` (`rol` ASC) ,
  CONSTRAINT `fk_table1_modulos`
    FOREIGN KEY (`module` )
    REFERENCES `kaozend`.`modules` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_roles1`
    FOREIGN KEY (`rol` )
    REFERENCES `kaozend`.`roles` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`notes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user` INT(11) NOT NULL ,
  `title` VARCHAR(100) NULL DEFAULT NULL ,
  `content` LONGTEXT NULL DEFAULT NULL ,
  `register_date` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ntes_users1_idx` (`user` ASC) ,
  CONSTRAINT `fk_ntes_users1`
    FOREIGN KEY (`user` )
    REFERENCES `kaozend`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `kaozend`.`products_app`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`products_app` (
  `product` INT(11) NOT NULL ,
  `app` INT(11) NOT NULL ,
  PRIMARY KEY (`product`, `app`) ,
  INDEX `fk_table1_products2_idx` (`product` ASC) ,
  INDEX `fk_table1_apps1_idx` (`app` ASC) ,
  CONSTRAINT `fk_table1_apps1`
    FOREIGN KEY (`app` )
    REFERENCES `kaozend`.`apps` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_products2`
    FOREIGN KEY (`product` )
    REFERENCES `kaozend`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`products_measures`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`products_measures` (
  `product` INT(11) NOT NULL ,
  `measure` INT(11) NOT NULL ,
  PRIMARY KEY (`product`, `measure`) ,
  INDEX `fk_products_measures_products1_idx` (`product` ASC) ,
  INDEX `fk_products_measures_measures1_idx` (`measure` ASC) ,
  CONSTRAINT `fk_products_measures_measures1`
    FOREIGN KEY (`measure` )
    REFERENCES `kaozend`.`measures` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_measures_products1`
    FOREIGN KEY (`product` )
    REFERENCES `kaozend`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`products_relation`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kaozend`.`products_relation` (
  `product_principal` INT(11) NOT NULL ,
  `products_relation` INT(11) NOT NULL ,
  PRIMARY KEY (`product_principal`, `products_relation`) ,
  INDEX `fk_products_relation_products1_idx` (`product_principal` ASC) ,
  INDEX `fk_products_relation_products2_idx` (`products_relation` ASC) ,
  CONSTRAINT `fk_products_relation_products1`
    FOREIGN KEY (`product_principal` )
    REFERENCES `kaozend`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_relation_products2`
    FOREIGN KEY (`products_relation` )
    REFERENCES `kaozend`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
