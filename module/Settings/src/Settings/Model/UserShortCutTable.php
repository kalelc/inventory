<?php
namespace Settings\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\Predicate;

class UserShortCutTable
{
    protected $tableGateway;
    protected $featureSet;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->featureSet = $this->tableGateway->getFeatureSet()->getFeatureByClassName('Zend\Db\TableGateway\Feature\EventFeature');
    }

    public function fetchAll()
    {
        $select = new Select();
        $select->from($this->tableGateway->getTable());
        $select->columns(array('*'));
        $select->join('modules', "user_shortcuts.module = modules.id", array('module_name' => 'name'), 'inner');
        $select->join('users', "user_shortcuts.user = users.id", array('user_first_name' => 'first_name','user_last_name' => 'last_name'), 'inner');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
