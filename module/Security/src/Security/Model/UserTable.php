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
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function get($username, $status = null)
    {

        $select = new Select();
        $select->from('users');
        $select->columns(array('*'));
        $select->where($status === null ? array(
            'users.username' => $username
            ) : array(
            'users.username' => $username,
            'users.status' => $status
            ));

        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();

        return ! $row ? false : $row;
    }




    public function getTable()
    {
        return $this->tableGateway->getTable();
    }

}
