<?php
namespace Security\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\Predicate;

class UserTable
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
        $select->join('roles', "roles.id = users.rol", array('rol_name' => 'name'), 'inner');

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function get($username, $status = null)
    {

        $select = new Select();
        $select->from($this->tableGateway->getTable());
        $select->columns(array('*'));
        $select->where($status === null ? array('users.username' => $username) : array(
            'users.username' => $username,
            'users.status' => $status
            ));

        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();

        return ! $row ? false : $row;
    }
}
