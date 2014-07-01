<?php


require_once 'Zend/Db/Table/Abstract.php';

/**
 * Contiene los datos del perfil de un usuario registrado
 *
 * @version $Id: PerfilUsuario.php 1025 2008-04-21 01:04:03Z mancai $
 */
class PerfilUsuario extends Zend_Db_Table_Abstract
{
    protected $_name = 'registro_perfil';
    protected $_primary = 'id';

}