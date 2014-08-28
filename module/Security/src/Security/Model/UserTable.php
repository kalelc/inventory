<?php
namespace Security\Model;

use Application\Db\TableGateway;
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
        /*$this->memcached->setItem("listUsers",$resultSet);
        $this->memcached->flush();
        dump($resultSet,"object resultset");
        dumpx($this->memcached->getItem('listUsers'),"item");*/
        return $resultSet;
    }

    public function get($id, $status = null)
    {

        $select = new Select();
        $select->from($this->tableGateway->getTable());
        $select->columns(array('*'));
        $select->where($status === null ? array('users.id' => $id) : array(
            'users.id' => $id,
            'users.status' => $status
            ));

        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();

        return ! $row ? false : $row;
    }

    public function save(User $user)
    {
        $data = array(
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'picture' => $user->getPicture(),
            'signature' => $user->getSignature(),
            'rol' => $user->getRol(),
            'password' => $user->getPassword(),
            'hash' => $user->getPassword(),
            'status' => 0,
            );

        $id = (int)$user->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            return true;
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                return true;
            } else {
                return false;
            }
        }
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function setMemcached($memcached)
    {
        $this->memcached = $memcached;
        return $this;
    }

}
