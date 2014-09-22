CREATE  TABLE IF NOT EXISTS `kaozend`.`products_receive_inventory` (
  `details_receive_inventory` INT(11) NOT NULL ,
  `number` INT NOT NULL ,
  `serial` VARCHAR(255) NOT NULL ,
  `status` VARCHAR(45) NULL DEFAULT 0 ,
  PRIMARY KEY (`details_receive_inventory`, `number`, `serial`) ,
  INDEX `fk_products_receive_inventory_details_receive_inventory1` (`details_receive_inventory` ASC) ,
  CONSTRAINT `fk_products_receive_inventory_details_receive_inventory1`
    FOREIGN KEY (`details_receive_inventory` )
    REFERENCES `kaozend`.`details_receive_inventory` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
