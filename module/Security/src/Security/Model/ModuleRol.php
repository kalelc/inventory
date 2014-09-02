<?php
namespace Security\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ModuleRol implements InputFilterAwareInterface
{
	private $module;
	private $rol;
	private $permissions;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('module', $data)) $this->setModule($data['module']);
		if (array_key_exists('rol', $data)) $this->setRol($data['rol']);
		if (array_key_exists('permissions', $data)) $this->setPermissions($data['permissions']);
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{}

	public function getId(){
		return $this->id;
	}

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
        return $this;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }
}
