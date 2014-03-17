<?php
namespace Settings\Traits;

Trait SettingsTrait
{
	public function getModuleTable()
	{
		$moduleTable = "";
		if (!$moduleTable) {
			$moduleTable = $this->getServiceLocator()->get('Settings\Model\ModuleTable');
		}
		return $moduleTable;
	}

	public function getUserShortCutTable()
	{
		$userShortCutTable = "";
		if (!$userShortCutTable) {
			$userShortCutTable = $this->getServiceLocator()->get('Settings\Model\UserShortCutTable');
		}
		return $userShortCutTable;
	}

	public function getUserShortCutForm()
	{
		$userShortCutForm = "" ;
		if (!$userShortCutForm)	{
			$userShortCutForm = $this->getServiceLocator()->get("Settings\Form\UserShortCutForm");
		}
		return $userShortCutForm;
	}
}
?>