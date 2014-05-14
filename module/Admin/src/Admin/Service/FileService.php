<?php
namespace Admin\Service;


class FileService
{

	const INVALID_EXTENSION   = 'invalidExtension';
	const INVALID_SIZE = 'invalidSize';
	const UNKNOW_ERROR = 'UNKNOW_ERROR';

	const KILOBYTE = 'KB';
	const MEGABYTE = 'MB';
	const GIGABYTE = 'GB';

	protected $messageTemplates = array(
		self::INVALID_EXTENSION => "extension invalida",
		self::INVALID_SIZE		=> "tamano invalido",
		self::UNKNOW_ERROR 		=> "error desconocido",
		);

	protected $values = array(
		self::KILOBYTE => 1000,
		self::MEGABYTE => 1000000,
		self::GIGABYTE => 1000000000
		);

	protected $destination;
	protected $extension = array();
	protected $size = array();

	protected $message;

	public function __construct($destination = array(),$extension = array(),$size = array())
	{
		$this->destination 	= $destination;
		$this->extension 	= $extension;
		$this->size 		= $size;
	}

	public function setDestination($destination)
	{
		$this->destination = $destination;
		return $this;
	}

	public function getDestination()
	{
		return $this->destination;
	}

	public function setExtension($extension)
	{
		$this->extension = $extension;
		return $this;
	}

	public function setSize($size)
	{	
		$values  =  $this->values;

		foreach($size as $newKey => $newSize) {
			foreach($values as $key => $value) {
				if(strstr(strtoupper($newSize),$key,true)) {
					$result[$newKey] = strstr($newSize,$key,true) * $value;
				}
			}
		}

		$this->size = $result;
		return $this;
	}

	public function setMessage($key)
	{
		if(!empty($key)) {
			$this->message = $this->messageTemplates[$key] ;
		}
		else {
			$this->message = $this->messageTemplates[self::UNKNOW_ERROR];
		}
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function copy($file)
	{
		if(!empty($file['name'])) {
			$extension = substr($file['name'], strrpos($file['name'], '.')+1) ;

			if(array_search(strtolower($extension),$this->extension)===false){
				$this->setMessage(self::INVALID_EXTENSION);
			}

			if(!range($file['size'],$this->size))
				$this->setMessage(self::INVALID_SIZE);

			if(!$this->getMessage())
			{
				$prefix = substr(md5(uniqid(rand())),0,2);
				$name = $prefix.time().".".$extension ;

				if(!file_exists($this->destination))
					mkdir($this->destination);

				if(move_uploaded_file($file['tmp_name'],$this->destination."/".$name))
				   return $name;
				else
					return false;
			}
			else
				return false;
		}
		else
			return false;

	}

	public function delete($file)
	{
		if(!empty($file))
			@unlink($this->destination."/".$file) ;
	}
}
?>