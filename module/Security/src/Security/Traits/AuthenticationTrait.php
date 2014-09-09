<?php
namespace Security\Traits;

use Zend\Authentication\AuthenticationService;

Trait AuthenticationTrait
{
	public function getAuthenticateValidate()
	{
		$authenticationService = new AuthenticationService();
		if(!$authenticationService->hasIdentity()){
			return $this->redirect()->toRoute('security/login');
		}
		else {
			$userObject = $authenticationService->getStorage()->read();
			$acl = unserialize($userObject->acl);

			if(!$acl->hasResource("user")) {
				return $this->redirect()->toRoute('security/login');
			}
		}
	}
}
?>














