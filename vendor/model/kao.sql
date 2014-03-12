SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `kaozend` DEFAULT CHARACTER SET latin1 ;
USE `kaozend` ;

-- -----------------------------------------------------
-- Table `kaozend`.`master_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`master_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `image` VARCHAR(45) NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `master_category` INT(11) NOT NULL,
  `singular_name` VARCHAR(120) NOT NULL,
  `plural_name` VARCHAR(120) NOT NULL,
  `image` VARCHAR(60) NULL,
  `shipping_cost` FLOAT NOT NULL,
  `additional_shipping` FLOAT NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_categorias_clasificaciones1_idx` (`master_category` ASC),
  CONSTRAINT `fk_categorias_clasificaciones1`
    FOREIGN KEY (`master_category`)
    REFERENCES `kaozend`.`master_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`specifications_masters`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`specifications_masters` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `image` VARCHAR(45) NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`specifications`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`specifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `specification_master` INT NOT NULL,
  `image` VARCHAR(45) NULL DEFAULT NULL,
  `meaning` LONGTEXT NULL DEFAULT NULL,
  `general_information` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_especificaciones_especificaciones_maestras1_idx` (`specification_master` ASC),
  CONSTRAINT `fk_especificaciones_especificaciones_maestras1`
    FOREIGN KEY (`specification_master`)
    REFERENCES `kaozend`.`specifications_masters` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`payments_methods`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`payments_methods` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  `bank_info` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_bin;


-- -----------------------------------------------------
-- Table `kaozend`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(120) NOT NULL,
  `last_name` VARCHAR(120) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `picture` VARCHAR(60) NULL,
  `signature` VARCHAR(60) NULL,
  `rol` INT(11) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `hash` VARCHAR(100) NOT NULL,
  `status` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_usuarios_roles1_idx` (`rol` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  CONSTRAINT `fk_usuarios_roles1`
    FOREIGN KEY (`rol`)
    REFERENCES `kaozend`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'password encriptado con md5\nfechas timestamp';


-- -----------------------------------------------------
-- Table `kaozend`.`logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `table` VARCHAR(40) NOT NULL,
  `operation` INT(11) NOT NULL COMMENT '1,2,3,4 constantes',
  `id_table` INT(11) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_logs_usuarios1_idx` (`user` ASC),
  CONSTRAINT `fk_logs_usuarios1`
    FOREIGN KEY (`user`)
    REFERENCES `kaozend`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`brands`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`brands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `image` VARCHAR(45) NULL,
  `background_image` VARCHAR(45) NULL COMMENT 'esta variable  o foto se va a usar para hacer el manual del producto el fondo de la vitrina ',
  `description` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`measures_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`measures_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `abbreviation` VARCHAR(60) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`measures`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`measures` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `specification` INT(11) NOT NULL,
  `measure_type` INT(11) NOT NULL,
  `measure_value` VARCHAR(120) NOT NULL,
  `image` VARCHAR(45) NULL,
  `meaning` LONGTEXT NULL DEFAULT NULL,
  `general_information` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_table1_unidades_medidas1_idx` (`measure_type` ASC),
  INDEX `fk_medidas_especificaciones1_idx` (`specification` ASC),
  CONSTRAINT `fk_table1_unidades_medidas1`
    FOREIGN KEY (`measure_type`)
    REFERENCES `kaozend`.`measures_types` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_medidas_especificaciones1`
    FOREIGN KEY (`specification`)
    REFERENCES `kaozend`.`specifications` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`modules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`modules` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `dependence` INT NOT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  `estatus` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`name` ASC),
  INDEX `fk_modulos_dependencias1_idx` (`dependence` ASC),
  CONSTRAINT `fk_modulos_dependencias1`
    FOREIGN KEY (`dependence`)
    REFERENCES `kaozend`.`dependencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`modules_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`modules_roles` (
  `modulo` INT(11) NOT NULL,
  `rol` INT(11) NOT NULL,
  `permission_type` INT(11) NOT NULL COMMENT '1: consultar,2 crear,3 actualizar, 4 borrar',
  PRIMARY KEY (`modulo`, `rol`, `permission_type`),
  INDEX `fk_table1_modulos_idx` (`modulo` ASC),
  INDEX `fk_table1_roles1_idx` (`rol` ASC),
  CONSTRAINT `fk_table1_modulos`
    FOREIGN KEY (`modulo`)
    REFERENCES `kaozend`.`modules` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_roles1`
    FOREIGN KEY (`rol`)
    REFERENCES `kaozend`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `upc_bar_code` INT(10) UNSIGNED NOT NULL,
  `model` VARCHAR(60) NOT NULL,
  `brand` INT(11) NOT NULL,
  `category` INT(11) NOT NULL,
  `part_no` VARCHAR(60) NULL,
  `price` FLOAT NULL,
  `iva` FLOAT NULL COMMENT '0,5,10,16',
  `qty_low` INT NULL,
  `qty_buy` INT NULL,
  `description` LONGTEXT NULL,
  `specification_file` VARCHAR(45) NULL,
  `image1` VARCHAR(45) NULL,
  `image2` VARCHAR(45) NULL,
  `image3` VARCHAR(45) NULL,
  `image4` VARCHAR(45) NULL,
  `image5` VARCHAR(45) NULL,
  `image6` VARCHAR(45) NULL,
  `manual_file` VARCHAR(45) NULL,
  `video` VARCHAR(45) NULL COMMENT 'nuevo\nusado\nremanufacturado\nnew pull',
  `status` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_productos_marcas1_idx` (`brand` ASC),
  INDEX `fk_productos_categorias1` (`category` ASC),
  CONSTRAINT `fk_productos_marcas1`
    FOREIGN KEY (`brand`)
    REFERENCES `kaozend`.`brands` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_productos_categorias1`
    FOREIGN KEY (`category`)
    REFERENCES `kaozend`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `identification` VARCHAR(20) NULL,
  `identification_type` INT(11) NOT NULL,
  `first_name` VARCHAR(120) NOT NULL,
  `last_name` VARCHAR(120) NOT NULL,
  `address` VARCHAR(120) NULL,
  `address2` VARCHAR(120) NULL,
  `zipcode` INT NULL,
  `company` VARCHAR(120) NULL DEFAULT NULL,
  `manager` VARCHAR(120) NULL,
  `web_page` VARCHAR(120) NULL,
  `date_of_birth` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `alias` VARCHAR(45) NOT NULL,
  `description` LONGTEXT NULL,
  `authorized` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_authorized`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customers_authorized` (
  `customer` INT(11) NOT NULL,
  `image` VARCHAR(45) NULL,
  `name` VARCHAR(120) NOT NULL,
  `last_name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(60) NULL,
  `telephone` VARCHAR(20) NULL,
  `status` INT(11) NOT NULL,
  INDEX `fk_terceros_personas_autorizadas_terceros1_idx` (`customer` ASC),
  CONSTRAINT `fk_terceros_personas_autorizadas_terceros1`
    FOREIGN KEY (`customer`)
    REFERENCES `kaozend`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_emails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customers_emails` (
  `customer` INT(11) NOT NULL,
  `email` INT(11) NOT NULL,
  PRIMARY KEY (`email`),
  INDEX `fk_terceros_emails_terceros1_idx` (`customer` ASC),
  CONSTRAINT `fk_terceros_emails_terceros1`
    FOREIGN KEY (`customer`)
    REFERENCES `kaozend`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`customer_phones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customer_phones` (
  `costumer` INT(11) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `phone_type` INT(11) NOT NULL COMMENT '1 celular,2 casa 3 trabajo',
  PRIMARY KEY (`phone`, `phone_type`),
  CONSTRAINT `fk_table1_terceros1`
    FOREIGN KEY (`costumer`)
    REFERENCES `kaozend`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`purchase_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`purchase_details` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cantidad` INT NOT NULL,
  `producto` INT NOT NULL,
  `vr_unit` INT NOT NULL,
  `garantia` INT NOT NULL COMMENT 'es  una constante los tiempos de garantia',
  `imagen_manifiesto` INT NULL,
  `seriales` INT NOT NULL,
  `observaciones` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`purchase`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`purchase` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha_hora` VARCHAR(120) NOT NULL,
  `tercero` INT NOT NULL,
  `forma_pago` INT NOT NULL,
  `metodo_envio` INT NOT NULL,
  `no_guia` VARCHAR(45) NULL,
  `imagen_faccompra` VARCHAR(45) NULL,
  `imagen_pago` VARCHAR(45) NULL,
  `descripcion` LONGTEXT NULL,
  `detalles_compra_id` INT NOT NULL,
  PRIMARY KEY (`id`, `detalles_compra_id`),
  INDEX `fk_compras_detalles_compra1_idx` (`detalles_compra_id` ASC),
  CONSTRAINT `fk_compras_detalles_compra1`
    FOREIGN KEY (`detalles_compra_id`)
    REFERENCES `kaozend`.`purchase_details` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`imange_manifiesto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`imange_manifiesto` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `imagen_manifiesto` VARCHAR(45) NOT NULL,
  `detalles_compra_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `detalles_compra_id`),
  INDEX `fk_manifiesto_detalles_compra1_idx` (`detalles_compra_id` ASC),
  CONSTRAINT `fk_manifiesto_detalles_compra1`
    FOREIGN KEY (`detalles_compra_id`)
    REFERENCES `kaozend`.`purchase_details` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `kaozend`.`request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`request` (
  `id` INT NOT NULL,
  `fecha_hora` VARCHAR(45) NOT NULL,
  `tercero` INT NOT NULL,
  `forma_pago` INT NOT NULL,
  `imagen_pago` VARCHAR(45) NULL,
  `descripcion` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`request_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`request_details` (
  `id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `producto` INT NOT NULL,
  `observaciones` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`estado2_compra_pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`estado2_compra_pedidos` (
  `id` INT NOT NULL,
  `fecha_hora` VARCHAR(45) NOT NULL,
  `no_producto` INT NOT NULL,
  `no_guia` VARCHAR(45) NULL,
  `cant_comprada` INT NOT NULL,
  `costo` DOUBLE(10,2) NOT NULL,
  `garantia_proveedor` INT NOT NULL,
  `tiempo_entrega` INT NOT NULL,
  `observacion` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`estado3_pedido_recibido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`estado3_pedido_recibido` (
  `id` INT NOT NULL,
  `fecha_hora` VARCHAR(45) NOT NULL,
  `orden_compra` VARCHAR(45) NOT NULL,
  `cantidad_recibida` INT NOT NULL,
  `serial` INT NOT NULL,
  `imagen_factcompra` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`forms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`forms` (
  `id` INT NOT NULL,
  `general` VARCHAR(45) NULL,
  `etiquetas_envio` VARCHAR(45) NULL,
  `entradas` VARCHAR(45) NULL,
  `salidas` VARCHAR(45) NULL,
  `facturas` VARCHAR(45) NULL,
  `prestamos` VARCHAR(45) NULL,
  `garantias_cartas` VARCHAR(45) NULL,
  `garantias_entradas` VARCHAR(45) NULL,
  `garantias_salida` VARCHAR(45) NULL,
  `pagos_internet` VARCHAR(45) NULL,
  `pedidos_producto` VARCHAR(45) NULL,
  `pedidos_entrega` VARCHAR(45) NULL,
  `pedidos_hoja_vida` VARCHAR(45) NULL,
  `servicios_entrada` VARCHAR(45) NULL,
  `servicios_salida` VARCHAR(45) NULL,
  `inventario_hoja_vida` VARCHAR(45) NULL,
  `inventario_pedidos` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`variables_sistema`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`variables_sistema` (
  `id` INT NOT NULL,
  `empresa` VARCHAR(45) NULL,
  `nit` VARCHAR(45) NULL,
  `tipo_empresa` INT NULL COMMENT 'constante definir tipos de empresa',
  `representante_legal` VARCHAR(45) NULL,
  `direccion1` VARCHAR(45) NULL,
  `direccion2` VARCHAR(45) NULL,
  `telefono` VARCHAR(45) NULL COMMENT 'no se si se pueda poner para 1 o n telefonos',
  `email_contactenos` VARCHAR(45) NULL,
  `texto_contactenos` LONGTEXT NULL,
  `email_compras` VARCHAR(45) NULL,
  `texto_compras` LONGTEXT NULL,
  `sistemacol` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`social_networks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`social_networks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `image` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_services_webs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customers_services_webs` (
  `web_service` INT NOT NULL,
  `costumer` INT(11) NOT NULL,
  `name` VARCHAR(102) NULL,
  INDEX `fk_terceros_servicios_webs_terceros1_idx` (`costumer` ASC),
  CONSTRAINT `fk_terceros_servicios_webs_servicios_web1`
    FOREIGN KEY (`web_service`)
    REFERENCES `kaozend`.`social_networks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_terceros_servicios_webs_terceros1`
    FOREIGN KEY (`costumer`)
    REFERENCES `kaozend`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`dependencies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`dependencies` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  `description` LONGTEXT NULL,
  `status` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`specifications_products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`specifications_products` (
  `specification` INT(11) NOT NULL,
  `product` INT(11) NOT NULL,
  INDEX `fk_productos_especificaciones_especificaciones1` (`specification` ASC),
  INDEX `fk_productos_especificaciones_productos1` (`product` ASC),
  PRIMARY KEY (`specification`, `product`),
  CONSTRAINT `fk_productos_especificaciones_especificaciones1`
    FOREIGN KEY (`specification`)
    REFERENCES `kaozend`.`specifications` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_productos_especificaciones_productos1`
    FOREIGN KEY (`product`)
    REFERENCES `kaozend`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`user_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`user_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`list_prices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`list_prices` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL,
  `description` LONGTEXT NULL,
  `principal` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customers_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `classification` INT NOT NULL,
  `lists_price` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tipos_terceros_clasificaciones1` (`classification` ASC),
  INDEX `fk_customers_type_lists_price1_idx` (`lists_price` ASC),
  CONSTRAINT `fk_tipos_terceros_clasificaciones1`
    FOREIGN KEY (`classification`)
    REFERENCES `kaozend`.`user_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_customers_type_lists_price1`
    FOREIGN KEY (`lists_price`)
    REFERENCES `kaozend`.`list_prices` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`customers_types_customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`customers_types_customer` (
  `costumer_type` INT NOT NULL,
  `costumer` INT(11) NOT NULL,
  INDEX `fk_terceros_tiposterceros_tipos_terceros1` (`costumer_type` ASC),
  INDEX `fk_terceros_tiposterceros_terceros1` (`costumer` ASC),
  PRIMARY KEY (`costumer_type`, `costumer`),
  CONSTRAINT `fk_terceros_tiposterceros_tipos_terceros1`
    FOREIGN KEY (`costumer_type`)
    REFERENCES `kaozend`.`customers_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_terceros_tiposterceros_terceros1`
    FOREIGN KEY (`costumer`)
    REFERENCES `kaozend`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`banks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`banks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`products_lists_price`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`products_lists_price` (
  `product` INT(11) NOT NULL,
  `lists_price` INT(11) NOT NULL,
  `price` FLOAT NOT NULL,
  INDEX `fk_table1_products1_idx` (`product` ASC),
  INDEX `fk_table1_lists_price1_idx` (`lists_price` ASC),
  PRIMARY KEY (`product`, `lists_price`),
  CONSTRAINT `fk_table1_products1`
    FOREIGN KEY (`product`)
    REFERENCES `kaozend`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_lists_price1`
    FOREIGN KEY (`lists_price`)
    REFERENCES `kaozend`.`list_prices` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`serials_name`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`serials_name` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`categories_serials_name`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`categories_serials_name` (
  `category` INT(11) NOT NULL,
  `serial_name` INT(11) NOT NULL,
  INDEX `fk_table1_categories1_idx` (`category` ASC),
  INDEX `fk_table1_serials_name1_idx` (`serial_name` ASC),
  PRIMARY KEY (`category`, `serial_name`),
  CONSTRAINT `fk_table1_categories1`
    FOREIGN KEY (`category`)
    REFERENCES `kaozend`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_serials_name1`
    FOREIGN KEY (`serial_name`)
    REFERENCES `kaozend`.`serials_name` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`cities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`cities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`products_relation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`products_relation` (
  `product_principal` INT(11) NOT NULL,
  `products_relation` INT(11) NOT NULL,
  INDEX `fk_products_relation_products1_idx` (`product_principal` ASC),
  INDEX `fk_products_relation_products2_idx` (`products_relation` ASC),
  PRIMARY KEY (`product_principal`, `products_relation`),
  CONSTRAINT `fk_products_relation_products1`
    FOREIGN KEY (`product_principal`)
    REFERENCES `kaozend`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_relation_products2`
    FOREIGN KEY (`products_relation`)
    REFERENCES `kaozend`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`apps`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`apps` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NULL,
  `imagen` VARCHAR(45) NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`products_app`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`products_app` (
  `product` INT NOT NULL,
  `app` INT NOT NULL,
  INDEX `fk_table1_products2_idx` (`product` ASC),
  INDEX `fk_table1_apps1_idx` (`app` ASC),
  PRIMARY KEY (`product`, `app`),
  CONSTRAINT `fk_table1_products2`
    FOREIGN KEY (`product`)
    REFERENCES `kaozend`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_apps1`
    FOREIGN KEY (`app`)
    REFERENCES `kaozend`.`apps` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kaozend`.`categories_specifications`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaozend`.`categories_specifications` (
  `category` INT(11) NOT NULL,
  `specification` INT(11) NOT NULL,
  `order` INT NULL,
  INDEX `fk_categories_specifications_categories1_idx` (`category` ASC),
  INDEX `fk_categories_specifications_specifications1_idx` (`specification` ASC),
  PRIMARY KEY (`category`, `specification`),
  INDEX `order` (`order` ASC),
  CONSTRAINT `fk_categories_specifications_categories1`
    FOREIGN KEY (`category`)
    REFERENCES `kaozend`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_categories_specifications_specifications1`
    FOREIGN KEY (`specification`)
    REFERENCES `kaozend`.`specifications` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
