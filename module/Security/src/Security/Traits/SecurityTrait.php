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

	public function getLogTable()
	{
		$logTable = "";
		if (!$logTable) {
			$logTable = $this->getServiceLocator()->get('Security\Model\LogTable');
		}
		return $logTable;
	}

	public function getModuleTable()
	{
		$moduleTable = "";
		if (!$moduleTable) {
			$moduleTable = $this->getServiceLocator()->get('Security\Model\ModuleTable');
		}
		return $moduleTable;
	}

	public function getModuleRolTable()
	{
		$moduleRolTable = "";
		if (!$moduleRolTable) {
			$moduleRolTable = $this->getServiceLocator()->get('Security\Model\ModuleRolTable');
		}
		return $moduleRolTable;
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