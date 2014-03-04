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
				'label' => 'Nombre de Usuario',
				),
			));

		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type'  => 'password',
				'class' => 'form-control',
				'placeholder' => 'Contraseña'
				),
			'options' => array(
				'label' => 'contraseña',
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

// Create our own session container so that we don't use end up with
// old captcha information in the session.
//$container = new Container('UserForm_Captcha');
//$container->setExpirationHops(1);
//$container->setExpirationSeconds(300);
//$captchaImage->setSession($container);

		$this->add(array(
			'type' => 'Zend\Form\Element\Captcha',
			'attributes' => array(
				'type'  => 'password',
				'class' => 'form-control',
				'placeholder' => 'Codigo de seguridad'
				),
			'name' => 'captcha',
			'options' => array(
				'captcha' => $captchaImage,
				'id' => 'captcha',
				'label' => 'Codigo de Seguridad',
				),
			));
			/*
			$this->add(array(
			'type' => 'Zend\Form\Element\Captcha',
			'name' => 'captcha',
			'options' => array(
			'wordLen' => 2, 
			'timeout' => 300, 

			'label' => 'Please verify you are human. ',
			'captcha' => array(
			'class' => 'Figlet',
			),
			),
			)
			);*/

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