<?php
namespace Admin\Validator;

use Zend\Validator\Db\RecordExists;

class DocumentCompositeKeyValidator
{

    const VALUES_EXIST = "valuesExist";

    const UNKNOW = "unknow";

    private $messageTemplates = array(
        self::VALUES_EXIST => "La combinación documento-tipo de documento ya existe.",
        self::UNKNOW => "error desconocido"
        );

    private $message;

    private $validatorCompositeKey;

    public function __construct($options)
    {
        $adapter = $options['adapter'];
        
        $clause = $adapter->getPlatform()->quoteIdentifier('identification_type') . ' = ' . $adapter->getPlatform()->quoteValue($options['documentTypeId']);
        $validatorCompositeKey = new RecordExists(array(
            'table' => 'customers',
            'field' => 'identification',
            'adapter' => $options['adapter'],
            'exclude' => $clause
            ));
        $this->setValidatorCompositeKey($validatorCompositeKey);
    }

    public function setValidatorCompositeKey($validatorCompositeKey)
    {
        $this->validatorCompositeKey = $validatorCompositeKey;
    }

    public function getMessageTemplate($messageKey = null)
    {
        if ($messageKey === null)
            return "error message key";
        else
            return $this->messageTemplates[$messageKey];
    }

    public function isValid($value)
    {
        if ($this->validatorCompositeKey->isValid($value)) {
            $this->setMessage($this->getMessageTemplate(self::VALUES_EXIST));
            return true;
        } else
        return false;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
?>