CREATE  TABLE IF NOT EXISTS `kaozend`.`details_output_inventory` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `output_inventory` INT(11) NOT NULL ,
  `qty` INT NULL ,
  `cost` FLOAT NULL ,
  `iva` INT NULL ,
  `product` INT(11) NOT NULL ,
  `register_date` TIMESTAMP NULL ,
  `update_date` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_details_output_inventory_output_inventory1` (`output_inventory` ASC) ,
  INDEX `fk_details_output_inventory_products1` (`product` ASC) ,
  CONSTRAINT `fk_details_output_inventory_output_inventory1`
    FOREIGN KEY (`output_inventory` )
    REFERENCES `kaozend`.`output_inventory` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_details_output_inventory_products1`
    FOREIGN KEY (`product` )
    REFERENCES `kaozend`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
