<?php
namespace Security\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Casper\Model\OAuthTools;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

class User implements InputFilterAwareInterface
{

    private $id;
    private $firstName;
    private $lastName;
    private $username;
    private $email;
    private $picture;
    private $signature;
    private $rol;
    private $password;
    private $status;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        if (array_key_exists('id', $data)) $this->setId($data['id']);
        if (array_key_exists('first_name', $data)) $this->setFirstName($data['first_name']);
        if (array_key_exists('last_name', $data)) $this->setLastName($data['last_name']);
        if (array_key_exists('username', $data)) $this->setUsername($data['username']);
        if (array_key_exists('email', $data)) $this->setEmail($data['email']);
        if (array_key_exists('picture', $data)) $this->setPicture($data['picture']);
        if (array_key_exists('signature', $data)) $this->setSignature($data['signature']);
        if (array_key_exists('rol', $data)) $this->setRol($data['rol']);
        if (array_key_exists('password', $data)) $this->setPassword($data['password']);
        if (array_key_exists('status', $data)) $this->setStatus($data['status']);
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
                'name' => 'first_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    ),
                'validators' => array(
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-ZñÑÁÉÍÓÚáéíóú ]*$/',
                            'messages' => array(
                                "regexInvalid" => 'No utilice caracteres especiales o numeros en este campo',
                                "regexNotMatch" => 'No utilice caracteres especiales o numeros en este campo',
                                "regexErrorous" => 'No utilice caracteres especiales o numeros en este campo'
                                )
                            )
                        ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'El campo es obligatorio'
                                )
                            )
                        )
                    )
                )
            ));

    $inputFilter->add($factory->createInput(array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-ZñÑÁÉÍÓÚáéíóú ]*$/',
                            'messages' => array(
                                "regexInvalid" => 'No utilice caracteres especiales o numeros en este campo',
                                "regexNotMatch" => 'No utilice caracteres especiales o numeros en este campo',
                                "regexErrorous" => 'No utilice caracteres especiales o numeros en este campo'
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'El campo es obligatorio'
                            )
                        )
                    )
                )
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'username',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 20,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'El usuario debe ser superior a %min% caracteres',
                                \Zend\Validator\StringLength::TOO_LONG => 'El usuario debe ser inferior a %max% caracteres'
                            )
                        )
                    ),
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-Z0-9_-]*$/',
                            'messages' => array(
                                "regexInvalid" => 'No utilice caracteres especiales en este campo',
                                "regexNotMatch" => 'No utilice caracteres especiales en este campo',
                                "regexErrorous" => 'No utilice caracteres especiales en este campo'
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Este no es un nombre para comentar válido.'
                            )
                        )
                    ),
                    array(
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                            'table' => 'users',
                            'field' => 'username',
                            'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                            'messages' => array(
                                \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => "El nombre para comentar que intentaste ingresar ya está asociado a otra cuenta de usuario, ingresa uno nuevo."
                            )
                        )
                    )
                )
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 8,
                            'max' => 100,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'La contraseña que ingresaste no es válida. Recuerda que esta debe tener mínimo 8 caracteres.'
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => ''
                            )
                        )
                    )
                )
            )
            ));
            $inputFilter->add($factory->createInput(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'EmailAddress'),
                    array('name' => 'NotEmpty'),
                    array(
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                            'table' => 'users',
                            'field' => 'email',
                            'exclude' => $this->getExcludeEmail(),
                            'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                            'messages' => array(
                                \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => "El correo electrónico que ingresaste ya está asociado a otra cuenta de un usuario, ingresa uno nuevo."
                            )
                        )
                    )
                )
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'picture',
                'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'signature',
                'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'rol',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Es necesario que selecciones un tipo de documento.'
                                )
                            )
                        )
                    )
                )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 8,
                            'max' => 100,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'La contraseña que ingresaste no es válida. Recuerda que esta debe tener mínimo 8 caracteres.'
                                )
                            )
                        ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => ''
                                )
                            )
                        )
                    )
                )
            ));
        }
    return $this->inputFilter;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    public function getPicture()
    {
        return $this->picture;
    }
    public function setPicture($picture)
    {
        $this->picture = $picture;
        return $this;
    }
    public function getSignature()
    {
        return $this->signature;
    }
    public function setSignature($signature)
    {
        $this->signature = $signature;
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
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}