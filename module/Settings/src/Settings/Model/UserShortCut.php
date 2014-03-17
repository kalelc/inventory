<?php
namespace Settings\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UserShortCut implements InputFilterAwareInterface
{
    private $modules;
    private $user;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        if (array_key_exists('modules', $data)) $this->setModules($data['modules']);
        if (array_key_exists('user', $data)) $this->setUser($data['user']);
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
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'modules',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Es necesario que selecciones un modulo'
                                )
                            )
                        )
                    )
                )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'user',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Es necesario que selecciones un usuario'
                                )
                            )
                        )
                    )
                )));
        }

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function setModules($modules)
    {
        $this->modules = $modules;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}