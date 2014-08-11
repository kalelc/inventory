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
	protected $partNo;
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

    protected $categoryName;
    protected $brandName;
    protected $singularName;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		if (array_key_exists('id', $data)) $this->setId($data['id']);
		if (array_key_exists('upc_bar_code', $data)) $this->setUpcBarCode($data['upc_bar_code']);
		if (array_key_exists('model', $data)) $this->setModel($data['model']);
		if (array_key_exists('brand', $data)) $this->setBrand($data['brand']);
		if (array_key_exists('category', $data)) $this->setCategory($data['category']);
		if (array_key_exists('part_no', $data)) $this->setPartNo($data['part_no']);
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
        if (array_key_exists('category_name', $data)) $this->setCategoryName($data['category_name']);
		if (array_key_exists('brand_name', $data)) $this->setBrandName($data['brand_name']);
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
								\Zend\Validator\NotEmpty::IS_EMPTY => ''
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
                    /*
                    array(
                        'name'    => 'Barcode',
                        'options' => array(
                            'adapter' => 'EAN13',
                            'checksum' => false,
                            'messages' => array(
                                \Zend\Validator\Barcode::INVALID=> 'el codigo ingresado es invalido',
                                \Zend\Validator\Barcode::INVALID_CHARS=> 'Los caracteres ingresados son invalidos',
                                \Zend\Validator\Barcode::INVALID_LENGTH=> 'el tamaño de caracteres es invalido',
                                ),
                            ),
                        ),
                        */
                    ),

				)));

            $inputFilter->add($factory->createInput(array(
                    'name'     => 'measures',
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
				'name'     => 'part_no',
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
								\Zend\Validator\NotEmpty::IS_EMPTY => ''
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
                'name' => 'iva',
                'required' => false,
            )));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'qty_low',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => ''
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
								\Zend\Validator\NotEmpty::IS_EMPTY => ''
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

    public function getId()
    {
    	return $this->id;
    }

    public function setId($id)
    {
    	$this->id = $id;
    	return $this;
    }

    public function getUpcBarCode()
    {
    	return $this->upcBarCode;
    }

    public function setUpcBarCode($upcBarCode)
    {
    	$this->upcBarCode = $upcBarCode;
    	return $this;
    }

    public function getModel()
    {
    	return $this->model;
    }

    public function setModel($model)
    {
    	$this->model = $model;
    	return $this;
    }

    public function getBrand()
    {
    	return $this->brand;
    }

    public function setBrand($brand)
    {
    	$this->brand = $brand;
    	return $this;
    }

    public function getCategory()
    {
    	return $this->category;
    }

    public function setCategory($category)
    {
    	$this->category = $category;
    	return $this;
    }


    public function getPrice()
    {
    	return $this->price;
    }

    public function setPrice($price)
    {
    	$this->price = $price;
    	return $this;
    }

    public function getIva()
    {
    	return $this->iva;
    }

    public function setIva($iva)
    {
    	$this->iva = $iva;
    	return $this;
    }

    public function getQtyLow()
    {
    	return $this->qtyLow;
    }

    public function setQtyLow($qtyLow)
    {
    	$this->qtyLow = $qtyLow;
    	return $this;
    }

    public function getQtyBuy()
    {
    	return $this->qtyBuy;
    }

    public function setQtyBuy($qtyBuy)
    {
    	$this->qtyBuy = $qtyBuy;
    	return $this;
    }

    public function getDescription()
    {
    	return $this->description;
    }

    public function setDescription($description)
    {
    	$this->description = $description;
    	return $this;
    }

    public function getSpecificationFile()
    {
    	return $this->specificationFile;
    }

    public function setSpecificationFile($specificationFile)
    {
    	$this->specificationFile = $specificationFile;
    	return $this;
    }

    public function getImage1()
    {
    	return $this->image1;
    }

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

    public function setImage2($image2)
    {
    	$this->image2 = $image2;
    	return $this;
    }

    public function getImage3()
    {
    	return $this->image3;
    }

    public function setImage3($image3)
    {
    	$this->image3 = $image3;
    	return $this;
    }

    public function getImage4()
    {
    	return $this->image4;
    }

    public function setImage4($image4)
    {
    	$this->image4 = $image4;
    	return $this;
    }

    public function getImage5()
    {
    	return $this->image5;
    }

    public function setImage5($image5)
    {
    	$this->image5 = $image5;
    	return $this;
    }


    public function getImage6()
    {
    	return $this->image6;
    }

    public function setImage6($image6)
    {
    	$this->image6 = $image6;
    	return $this;
    }

    public function getManualFile()
    {
    	return $this->manualFile;
    }

    public function setManualFile($manualFile)
    {
    	$this->manualFile = $manualFile;
    	return $this;
    }

    public function getVideo()
    {
    	return $this->video;
    }

    public function setVideo($video)
    {
    	$this->video = $video;
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

    public function getCategoryName()
    {
        return $this->categoryName;
    }
  
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
        return $this;
    }

    public function getBrandName()
    {
        return $this->brandName;
    }

    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;
        return $this;
    }

    public function getPartNo()
    {
        return $this->partNo;
    }

    public function setPartNo($partNo)
    {
        $this->partNo = $partNo;
        return $this;
    }

        public function getSingularName()
    {
        return $this->singularName;
    }

    public function setSingularName($singularName)
    {
        $this->singularName = $singularName;
        return $this;
    }
}
