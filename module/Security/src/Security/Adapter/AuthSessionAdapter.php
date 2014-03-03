<?php
namespace Security\Adapter;


use Zend\Authentication\Adapter\DbTable as AuthDbAdapter;
use Zend\Authentication\Result as AuthResult;
use Zend\Http\Client\Exception as HttpException;


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
		$username = "kalelc";
		$password = "123456";
		/*
		$user = $this->userTable->get($username,1);

		$bcrypt = new Bcrypt();
		$securePassword = $bcrypt->create($password);

		if($bcrypt->verify($password,$user->getPassword())){
			return true;
		}
		else
			return false;*/

		$authDbAdapter = new AuthDbAdapter($this->dbAdapter,'users','username','password' ,'MD5(?)AND status = 1');
		$authDbAdapter->setIdentity($username);
		$authDbAdapter->setCredential($password);

		dumpx($authDbAdapter);
		/*

		$authenticate = $authDbAdapter->authenticate();

		dumpx($authenticate,"authenticate");

		if($authenticate->isValid()) {
		dumpx($authDbAdapter->getResultRowObject());

			dumpx("es valido");
			$authService = new AuthenticationService();
			$authService->setStorage(new SessionStorage(SessionStorage::NAMESPACE_DEFAULT));
			$authService->setAdapter($authDbAdapter);
			$authService->authenticate();

			return $authService;
		}
		else {
			return $authenticate->getMessages();
		}*/
	}

	public function getAdapter()
	{
		return $this->adapter;
	}

	public function setAdapter($adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function getUserTable()
	{
		return $this->userTable;
	}

	public function setUserTable($userTable)
	{
		$this->userTable = $userTable;
		return $this;
	}

	public function getIdentity()
	{
		return $this->identity;
	}

	public function setIdentity($identity)
	{
		$this->identity = $identity;
		return $this;
	}

	public function getCredential()
	{
		return $this->credential;
	}

	public function setCredential($credential)
	{
		$this->credential = $credential;
		return $this;
	}
}
