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
use Zend\Crypt\Password\Bcrypt;

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
    private $rolName;
    private $password;
    private $passwordBcrypt;
    private $status;

    private $adapter;
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
        if (array_key_exists('rol_name', $data)) $this->setRolName($data['rol_name']);
        if (array_key_exists('password', $data)) $this->setPassword($data['password']);
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
            )));

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
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                        'table' => 'users',
                        'field' => 'username',
                        'exclude' => $this->getExcludeUserName(),
                        'adapter' => $this->getAdapter(),
                        'messages' => array(
                            \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => "El nombre de usuario ya existe."
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'nombre de usuario invalido.'
                                )
                            )
                        ),
                    )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Es necesario ingresar un email.'
                                )
                            )
                        ),
                    array(
                        'name' => 'EmailAddress'
                        ),
                    array(
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                            'table' => 'users',
                            'field' => 'email',
                            'exclude' => $this->getExcludeEmail(),
                            'adapter' => $this->getAdapter(),
                            'messages' => array(
                                \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => "El correo electrónico ya existe."
                                )
                            )
                        )
                    )
                )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'rol',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Es necesario seleccionar el rol.'
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
                                \Zend\Validator\StringLength::TOO_SHORT => 'La contraseña debe tener mínimo 8 caracteres.'
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
                        ),
                    )
                )));

    $this->inputFilter = $inputFilter;
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
        $bcrypt = new Bcrypt();
        $hash = $bcrypt->create($password);
        $this->password = $hash;
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
    public function getRolName()
    {
        return $this->rolName;
    }
    public function setRolName($rolName)
    {
        $this->rolName = $rolName;
        return $this;
    }
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getExcludeEmail()
    {
        $excludeEmail = null;
        if ($this->email) {
            $excludeEmail = array(
                'field' => 'email',
                'value' => $this->email
                );
        }
        return $excludeEmail;
    }

    public function getExcludeUserName()
    {
        $excludeUsername = null;
        if ($this->username) {
            $excludeUsername = array(
                'field' => 'username',
                'value' => $this->username
                );
        }
        return $excludeUsername;

    }
}