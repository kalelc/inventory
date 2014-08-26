<?php
namespace Security\Form;

use Zend\Form\Form;
use Zend\Form\Element;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Form\Element\Captcha as Captcha;

class LoginForm extends Form
{
	public function __construct()
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->add(array(
			'name' => 'username',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => 'nombre de usuario'
				),
			'options' => array(
				'label' => 'username',
				'label_attributes' => array(
					'class'  => 'col-sm-3 control-label'
					),	
				),
			));

		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type'  => 'password',
				'id' => 'password',
                'maxlength' => 20,
				'class' => 'form-control',
				'placeholder' => 'Contraseña'
				),
			'options' => array(
				'label' => 'password',
				'label_attributes' => array(
					'class'  => 'col-sm-3 control-label'
					),	
				),
			));

		$captchaImage = new CaptchaImage();
		$captchaImage->setFont("public/resource/fonts/arial.ttf");
		$captchaImage->setImgDir("public/resource/images");
		$captchaImage->setImgUrl("/resource/images");
		$captchaImage->setImgAlt("Captcha");
		$captchaImage->setWidth(250);
		$captchaImage->setHeight(80);
		$captchaImage->setDotNoiseLevel(50);
		$captchaImage->setLineNoiseLevel(5);
		$captchaImage->setFontSize(38);
		$captchaImage->setExpiration(1);
		$captchaImage->setWordlen(6);
		$captchaImage->setMessage("El código no coincide, actualízalo y vuelve a intentarlo",$captchaImage::BAD_CAPTCHA);

		$this->add(array(
			'type' => 'Zend\Form\Element\Captcha',
			'attributes' => array(
				'type'  => 'password',
				'class' => 'form-control',
				'placeholder' => 'captcha'
				),
			'name' => 'captcha',
			'options' => array(
				'captcha' => $captchaImage,
				'id' => 'captcha',
				'label' => 'captcha',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),	
				),
			));

		$this->add(array(
			'type' => 'Zend\Form\Element\Csrf',
			'name' => 'csrf',
			'options' => array(
				'csrf_options' => array(
					'timeout' => 600
					)
				)
			));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'Ingresar',
				'class' => 'btn btn-primary btn-sm btn-sm'
				),
			));
	}
}