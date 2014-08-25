<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Security\Model\User;
use Security\Form\UserForm;
use Security\Traits\SecurityTrait;
use Application\ConfigAwareInterface;

use Zend\Validator\File\Extension as FileExtension;
use Zend\Validator\File\Size as FileSize;

class UserController extends AbstractActionController
implements ConfigAwareInterface
{
	use SecurityTrait;
	
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'users' => $this->getUserTable()->fetchAll(),
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Security\Form\UserForm");

		$request = $this->getRequest();
		if ($request->isPost()) {

			$user = new User();
			$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$user->setAdapter($adapter);

			$data = $request->getPost()->toArray();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($data);
			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['user']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$picture = $fileService->copy($this->params()->fromFiles('picture'));
				$signature = $fileService->copy($this->params()->fromFiles('signature'));

				$data['picture'] = $picture ? $picture : "" ;
				$data['signature'] = $signature ? $signature : "" ;

				$user->exchangeArray($data);
				$this->getUserTable()->save($user);

				return $this->redirect()->toRoute('security/user');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config
			);
	}


	public function editAction()
	{

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('security/user', array('action' => 'add'));
		}
		$user = $this->getUserTable()->get($id);
		$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$user->setAdapter($adapter);

		$previousPicture = $user->getPicture();
		$previousSignature = $user->getSignature();
		$previousPassword = $user->getPassword();

		$form = $this->getServiceLocator()->get("Security\Form\UserForm");
		
		$form->get("first_name")->setValue($user->getFirstName());
		$form->get("last_name")->setValue($user->getLastName());
		$form->bind($user);

		$request = $this->getRequest();
		if ($request->isPost()) {

			

			$form->setInputFilter($user->getInputFilter());
			$userData = $request->getPost()->toArray();
			
			$password = $this->params()->fromPost('password');
			
			if($password!=="xxxxxxxxxxxx")
				$user->setPassword($password);

			$form->setData($userData);

			if ($form->isValid()) {

				$user->setFirstName($userData["first_name"]);
				$user->setLastName($userData["last_name"]);
				$user->setUsername($userData["username"]);
				$user->setEmail($userData["email"]);
				$user->setRol($userData["rol"]);
				
				dump($previousPassword);
				dumpx($user);

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['user']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$picture = $this->params()->fromFiles('picture');
				$signature = $this->params()->fromFiles('signature');

				if(isset($picture['name']) && !empty($picture['name'])) {
					$picture = $fileService->copy($picture);
					$user->setpicture($picture);
					if(isset($previousPicture) && !empty($previousPicture))
						@unlink($this->config['component']['user']['image_path']."/".$previousPicture);
				}

				if(isset($signature['name']) && !empty($signature['name'])) {
					$signature = $fileService->copy($signature);
					$user->setsignature($signature);
					if(isset($previousSignature) && !empty($previousSignature))
						@unlink($this->config['component']['user']['image_path']."/".$previousSignature);
				}

				$this->getUserTable()->save($user);
				return $this->redirect()->toRoute('security/user');
			}
		}

		return array(
			'id' => $id,
			'picture' => $previousPicture,
			'signature' => $previousSignature,
			'form' => $form,
			'config' => $this->config
			);
	
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('security/user');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				@unlink($this->config['component']['user']['image_path']."/".$this->getUserTable()->get($id)->getSignature());
				@unlink($this->config['component']['user']['image_path']."/".$this->getUserTable()->get($id)->getPicture());
				$this->getUserTable()->delete($id);
			}

			return $this->redirect()->toRoute('security/user');
		}
		return array(
			'id'=> $id,
			'user' => $this->getUserTable()->get($id),
			'config' => $this->config,
			);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}