CREATE  TABLE IF NOT EXISTS `kaozend`.`output_inventory` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user` INT(11) NOT NULL ,
  `client` INT(11) NOT NULL ,
  `seller` INT(11) NOT NULL ,
  `payment_method` INT(11) NOT NULL ,
  `guide` VARCHAR(45) NULL ,
  `observations` LONGTEXT NULL ,
  `register_date` TIMESTAMP NULL ,
  `update_date` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_output_inventory_users1` (`user` ASC) ,
  INDEX `fk_output_inventory_customers1` (`client` ASC) ,
  INDEX `fk_output_inventory_customers2` (`seller` ASC) ,
  INDEX `fk_output_inventory_payments_methods1` (`payment_method` ASC) ,
  CONSTRAINT `fk_output_inventory_users1`
    FOREIGN KEY (`user` )
    REFERENCES `kaozend`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_output_inventory_customers1`
    FOREIGN KEY (`client` )
    REFERENCES `kaozend`.`customers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_output_inventory_customers2`
    FOREIGN KEY (`seller` )
    REFERENCES `kaozend`.`customers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_output_inventory_payments_methods1`
    FOREIGN KEY (`payment_method` )
    REFERENCES `kaozend`.`payments_methods` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
