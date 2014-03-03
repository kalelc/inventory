<?php
namespace Security\Traits;

Trait SecurityTrait
{
	public function getUserTable()
	{
		$userTable = "";
		if (!$userTable) {
			$userTable = $this->getServiceLocator()->get('Security\Model\UserTable');
		}
		return $userTable;
	}
	public function getRolTable()
	{
		$rolTable = "";
		if (!$rolTable) {
			$rolTable = $this->getServiceLocator()->get('Security\Model\RolTable');
		}
		return $rolTable;
	}

	public function getAuthSessionAdapter()
	{
		$authSessionAdapter = "";
		if(!$authSessionAdapter){
			$authSessionAdapter = $this->getServiceLocator()->get("Security\Adapter\AuthSessionAdapter");
		}
		return $authSessionAdapter;
	}
}
?>