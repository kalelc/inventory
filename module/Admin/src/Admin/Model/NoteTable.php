<?php 
namespace Admin\Model;

use Application\Db\TableGateway;


class NoteTable
{

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
		$this->featureSet = $this->tableGateway->getFeatureSet()->getFeatureByClassName('Zend\Db\TableGateway\Feature\EventFeature');
	}

	public function fetchAll($user)
	{
		$resultSet = $this->tableGateway->select(array('user' => $user));
		$resultSet->buffer();
		return $resultSet;

	}

	public function get($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			return false;
		}
		return $row;
	}

	public function save(Note $note)
	{
		$data = array(
			'user' => $note->getUser(),
			'title' => $note->getTitle(),
			'content' => $note->getContent(),
			'register_date' => date("Y-m-d H:i:s", time()),
			);

		error_log(json_encode($data));

		$id = (int)$note->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id ;
		} else {
			if ($this->get($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				return false;
			}
		}
	}

	public function delete($id)
	{	
		try {
			$result = $this->tableGateway->delete(array('id' => $id));
		}
		catch(\Exception $e) {
			$result = false;
		}
		return $result;
	}
}