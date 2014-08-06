<?php
namespace Process\Traits;

Trait ModuleTablesTrait
{
	public function getReceiveInventoryTable()
	{
		$receiveInventoryTable = "";
		if (!$receiveInventoryTable) {
			$receiveInventoryTable = $this->getServiceLocator()->get('Process\Model\ReceiveInventoryTable');
		}
		return $receiveInventoryTable;
	}
}
?>