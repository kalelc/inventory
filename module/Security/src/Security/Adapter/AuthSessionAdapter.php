<?php
namespace Security\Adapter;


use Zend\Authentication\Adapter\DbTable as AuthDbAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;

class AuthSessionAdapter
{
	protected $dbAdapter;
	protected $identity;
	protected $credential;

	public function __construct($dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
	}

	public function authenticate($username,$password)
	{
		$bcrypt = new Bcrypt();

		$authDbAdapter = new AuthDbAdapter($this->dbAdapter,'users','username','password' ,'MD5(?)AND status = 1');
		$authDbAdapter->setIdentity($username);
		$authDbAdapter->setCredential($password);
		$authenticate = $authDbAdapter->authenticate();

		if($authenticate->isValid()) {

			$userValues = $authDbAdapter->getResultRowObject('hash');
			if($bcrypt->verify($password,$userValues->hash)) {

				$authService = new AuthenticationService();
				$authService->setStorage(new SessionStorage(SessionStorage::NAMESPACE_DEFAULT));
				$authService->setAdapter($authDbAdapter);
				$authService->authenticate();

				if($authService->authenticate()->isValid()) {
					return $authService;
				}
				else
					error_log("error al validar");

			}
			else 
				error_log("hash invalido");
		}
		else {
			return $authenticate->getMessages();
		}
	}

	public function hasIdentity()
	{
		$authService = new AuthenticationService();
		return $authService->hasIdentity();
	}

	public function getIdentity()
	{
		$authService = new AuthenticationService();
		return $authService->getIdentity();
	}

	public function clearIdentity()
	{
		$authService = new AuthenticationService();
		$authService->clearIdentity();
	}
}
