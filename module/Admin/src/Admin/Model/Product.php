<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Product implements InputFilterAwareInterface
{
	protected $id;
	protected $upcBarCode;
	protected $model;
	protected $brand;
	protected $category;
	protected $noPart;
	protected $price;
	protected $iva;
	protected $qtyLow;
	protected $qtyBuy;
	protected $description;
	protected $specificationFile;
	protected $image1;
	protected $image2;
	protected $image3;
	protected $image4;
	protected $image5;
	protected $image6;
	protected $manualFile;
	protected $video;
	protected $status;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('upc_bar_code', $data)) $this->setUpcBarCode($data['upc_bar_code']);
		if (array_key_exists('model', $data)) $this->setModel($data['model']);
		if (array_key_exists('brand', $data)) $this->setBrand($data['brand']);
		if (array_key_exists('category', $data)) $this->setCategory($data['category']);
		if (array_key_exists('no_part', $data)) $this->setNoPart($data['no_part']);
		if (array_key_exists('price', $data)) $this->setPrice($data['price']);
		if (array_key_exists('iva', $data)) $this->setIva($data['iva']);
		if (array_key_exists('qty_low', $data)) $this->setQtyLow($data['qty_low']);
		if (array_key_exists('qty_buy', $data)) $this->setQtyBuy($data['qty_buy']);
		if (array_key_exists('description', $data)) $this->setDescription($data['description']);
		if (array_key_exists('specification_file', $data)) $this->setSpecificationFile($data['specification_file']);
		if (array_key_exists('image1', $data)) $this->setImage1($data['image1']);
		if (array_key_exists('image2', $data)) $this->setImage2($data['image2']);
		if (array_key_exists('image3', $data)) $this->setImage3($data['image3']);
		if (array_key_exists('image4', $data)) $this->setImage4($data['image4']);
		if (array_key_exists('image5', $data)) $this->setImage5($data['image5']);
		if (array_key_exists('image6', $data)) $this->setImage6($data['image6']);
		if (array_key_exists('manual_file', $data)) $this->setManualFile($data['manual_file']);
		if (array_key_exists('video', $data)) $this->setVideo($data['video']);
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
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name'     => 'id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				)));



			$inputFilter->add($factory->createInput(array(
				'name'     => 'upc_bar_code',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
                    array(
                        'name'    => 'Digits',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Digits::NOT_DIGITS=> 'Debe ingresar un valor numerico',
                                \Zend\Validator\Digits::STRING_EMPTY=> 'Debe ingresar un valor numerico'
                                ),
                            ),
                        ),
                    ),
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'model',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					),

				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'brand',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					),

				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'category',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					),
				
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'no_part',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					),
				
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'price',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					array(
						'name'    => 'Digits',
						'options' => array(
							'messages' => array(
								\Zend\Validator\Digits::NOT_DIGITS=> 'Debe ingresar un valor numerico',
								\Zend\Validator\Digits::STRING_EMPTY=> 'Debe ingresar un valor numerico'
								),
							),
						),
					),
				
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'iva',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					array(
						'name'    => 'Digits',
						'options' => array(
							'messages' => array(
								\Zend\Validator\Digits::NOT_DIGITS=> 'Debe ingresar un valor numerico',
								\Zend\Validator\Digits::STRING_EMPTY=> 'Debe ingresar un valor numerico'
								),
							),
						),
					),
				
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'qty_low',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					array(
						'name'    => 'Digits',
						'options' => array(
							'messages' => array(
								\Zend\Validator\Digits::NOT_DIGITS=> 'Debe ingresar un valor numerico',
								\Zend\Validator\Digits::STRING_EMPTY=> 'Debe ingresar un valor numerico'
								),
							),
						)
					),
				
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'qty_buy',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'el campo no debe estar vacio'
								),
							),
						),
					array(
						'name'    => 'Digits',
						'options' => array(
							'messages' => array(
								\Zend\Validator\Digits::NOT_DIGITS=> 'Debe ingresar un valor numerico',
								\Zend\Validator\Digits::STRING_EMPTY=> 'Debe ingresar un valor numerico'
								),
							),
						)
					),
				
				)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'description',
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}



    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
    	$this->id = $id;

    	return $this;
    }

    /**
     * Gets the value of upcBarCode.
     *
     * @return mixed
     */
    public function getUpcBarCode()
    {
    	return $this->upcBarCode;
    }
    
    /**
     * Sets the value of upcBarCode.
     *
     * @param mixed $upcBarCode the upc bar code
     *
     * @return self
     */
    public function setUpcBarCode($upcBarCode)
    {
    	$this->upcBarCode = $upcBarCode;

    	return $this;
    }

    /**
     * Gets the value of model.
     *
     * @return mixed
     */
    public function getModel()
    {
    	return $this->model;
    }
    
    /**
     * Sets the value of model.
     *
     * @param mixed $model the model
     *
     * @return self
     */
    public function setModel($model)
    {
    	$this->model = $model;

    	return $this;
    }

    /**
     * Gets the value of brand.
     *
     * @return mixed
     */
    public function getBrand()
    {
    	return $this->brand;
    }
    
    /**
     * Sets the value of brand.
     *
     * @param mixed $brand the brand
     *
     * @return self
     */
    public function setBrand($brand)
    {
    	$this->brand = $brand;

    	return $this;
    }

    /**
     * Gets the value of category.
     *
     * @return mixed
     */
    public function getCategory()
    {
    	return $this->category;
    }
    
    /**
     * Sets the value of category.
     *
     * @param mixed $category the category
     *
     * @return self
     */
    public function setCategory($category)
    {
    	$this->category = $category;

    	return $this;
    }

    /**
     * Gets the value of noPart.
     *
     * @return mixed
     */
    public function getNoPart()
    {
    	return $this->noPart;
    }
    
    /**
     * Sets the value of noPart.
     *
     * @param mixed $noPart the no part
     *
     * @return self
     */
    public function setNoPart($noPart)
    {
    	$this->noPart = $noPart;

    	return $this;
    }

    /**
     * Gets the value of price.
     *
     * @return mixed
     */
    public function getPrice()
    {
    	return $this->price;
    }
    
    /**
     * Sets the value of price.
     *
     * @param mixed $price the price
     *
     * @return self
     */
    public function setPrice($price)
    {
    	$this->price = $price;

    	return $this;
    }

    /**
     * Gets the value of iva.
     *
     * @return mixed
     */
    public function getIva()
    {
    	return $this->iva;
    }
    
    /**
     * Sets the value of iva.
     *
     * @param mixed $iva the iva
     *
     * @return self
     */
    public function setIva($iva)
    {
    	$this->iva = $iva;

    	return $this;
    }

    /**
     * Gets the value of qtyLow.
     *
     * @return mixed
     */
    public function getQtyLow()
    {
    	return $this->qtyLow;
    }
    
    /**
     * Sets the value of qtyLow.
     *
     * @param mixed $qtyLow the qty low
     *
     * @return self
     */
    public function setQtyLow($qtyLow)
    {
    	$this->qtyLow = $qtyLow;

    	return $this;
    }

    /**
     * Gets the value of qtyBuy.
     *
     * @return mixed
     */
    public function getQtyBuy()
    {
    	return $this->qtyBuy;
    }
    
    /**
     * Sets the value of qtyBuy.
     *
     * @param mixed $qtyBuy the qty buy
     *
     * @return self
     */
    public function setQtyBuy($qtyBuy)
    {
    	$this->qtyBuy = $qtyBuy;

    	return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
    	return $this->description;
    }
    
    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
    	$this->description = $description;

    	return $this;
    }

    /**
     * Gets the value of specificationFile.
     *
     * @return mixed
     */
    public function getSpecificationFile()
    {
    	return $this->specificationFile;
    }
    
    /**
     * Sets the value of specificationFile.
     *
     * @param mixed $specificationFile the specification file
     *
     * @return self
     */
    public function setSpecificationFile($specificationFile)
    {
    	$this->specificationFile = $specificationFile;

    	return $this;
    }

    /**
     * Gets the value of image1.
     *
     * @return mixed
     */
    public function getImage1()
    {
    	return $this->image1;
    }
    
    /**
     * Sets the value of image1.
     *
     * @param mixed $image1 the image1
     *
     * @return self
     */
    public function setImage1($image1)
    {
    	$this->image1 = $image1;

    	return $this;
    }

    /**
     * Gets the value of image2.
     *
     * @return mixed
     */
    public function getImage2()
    {
    	return $this->image2;
    }
    
    /**
     * Sets the value of image2.
     *
     * @param mixed $image2 the image2
     *
     * @return self
     */
    public function setImage2($image2)
    {
    	$this->image2 = $image2;

    	return $this;
    }

    /**
     * Gets the value of image3.
     *
     * @return mixed
     */
    public function getImage3()
    {
    	return $this->image3;
    }
    
    /**
     * Sets the value of image3.
     *
     * @param mixed $image3 the image3
     *
     * @return self
     */
    public function setImage3($image3)
    {
    	$this->image3 = $image3;

    	return $this;
    }

    /**
     * Gets the value of image4.
     *
     * @return mixed
     */
    public function getImage4()
    {
    	return $this->image4;
    }
    
    /**
     * Sets the value of image4.
     *
     * @param mixed $image4 the image4
     *
     * @return self
     */
    public function setImage4($image4)
    {
    	$this->image4 = $image4;

    	return $this;
    }

    /**
     * Gets the value of image5.
     *
     * @return mixed
     */
    public function getImage5()
    {
    	return $this->image5;
    }
    
    /**
     * Sets the value of image5.
     *
     * @param mixed $image5 the image5
     *
     * @return self
     */
    public function setImage5($image5)
    {
    	$this->image5 = $image5;

    	return $this;
    }

    /**
     * Gets the value of image6.
     *
     * @return mixed
     */
    public function getImage6()
    {
    	return $this->image6;
    }
    
    /**
     * Sets the value of image6.
     *
     * @param mixed $image6 the image6
     *
     * @return self
     */
    public function setImage6($image6)
    {
    	$this->image6 = $image6;

    	return $this;
    }

    /**
     * Gets the value of manualFile.
     *
     * @return mixed
     */
    public function getManualFile()
    {
    	return $this->manualFile;
    }
    
    /**
     * Sets the value of manualFile.
     *
     * @param mixed $manualFile the manual file
     *
     * @return self
     */
    public function setManualFile($manualFile)
    {
    	$this->manualFile = $manualFile;

    	return $this;
    }

    /**
     * Gets the value of video.
     *
     * @return mixed
     */
    public function getVideo()
    {
    	return $this->video;
    }
    
    /**
     * Sets the value of video.
     *
     * @param mixed $video the video
     *
     * @return self
     */
    public function setVideo($video)
    {
    	$this->video = $video;

    	return $this;
    }

    /**
     * Gets the value of status.
     *
     * @return mixed
     */
    public function getStatus()
    {
    	return $this->status;
    }
    
    /**
     * Sets the value of status.
     *
     * @param mixed $status the status
     *
     * @return self
     */
    public function setStatus($status)
    {
    	$this->status = $status;

    	return $this;
    }
}
