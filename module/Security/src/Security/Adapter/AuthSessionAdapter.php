<?php
namespace Security\Adapter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\Authentication\Validator\Authentication as AuthenticationValidator;
use Zend\Crypt\Password\Bcrypt;

class AuthSessionAdapter
{
	protected $dbAdapter;
	protected $code;

	public function __construct($dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
	}

	public function authenticate($username,$password)
	{
		
		$callback = function($password, $hash) {
			$bcrypt = new Bcrypt();
			return $bcrypt->verify($hash, $password);
		};

		$authenticationService = new AuthenticationService();
		$callbackCheckAdapter = new CallbackCheckAdapter($this->dbAdapter,"users",'username','password',$callback);

		$callbackCheckAdapter->setIdentity($username)->setCredential($password);

		$authenticationService->setAdapter($callbackCheckAdapter);
		$authResult = $authenticationService->authenticate();

		if($authResult->isValid()) {
			$userObject = $callbackCheckAdapter->getResultRowObject();
			$authenticationService->getStorage()->write($userObject);

			if($userObject->status==0) {
				$this->setCode(-5);
				return false;
			}
			else {
				dumpx($userObject);
				//get rol name
				//get list modules and resources
				return true;
			}
		}
		else {
			$this->setCode($authResult->getCode());
			return false;
		}
	}

	public function getCode()
	{
		return $this->code;
	}

	protected function setCode($code)
	{
		$this->code = $code;

		return $this;
	}
}
