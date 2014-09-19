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

	public function getOutputInventoryTable()
	{
		$outputInventoryTable = "";
		if (!$outputInventoryTable) {
			$outputInventoryTable = $this->getServiceLocator()->get('Process\Model\OutputInventoryTable');
		}
		return $outputInventoryTable;
	}

	public function getDetailsReceiveInventoryTable()
	{
		$detailsReceiveInventoryTable = "";
		if (!$detailsReceiveInventoryTable) {
			$detailsReceiveInventoryTable = $this->getServiceLocator()->get('Process\Model\DetailsReceiveInventoryTable');
		}
		return $detailsReceiveInventoryTable;
	}


	public function getDetailsOutputInventoryTable()
	{
		$detailsOutputInventoryTable = "";
		if (!$detailsOutputInventoryTable) {
			$detailsOutputInventoryTable = $this->getServiceLocator()->get('Process\Model\DetailsOutputInventoryTable');
		}
		return $detailsOutputInventoryTable;
	}
}
?>