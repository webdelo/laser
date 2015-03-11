<?php
namespace modules\clients\lib;
class ClientPhisicConfig extends \modules\clients\lib\ClientConfig
{
	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty', array('Имя')),
			),
			'surname' => array(
				'validation' => array('_validNotEmpty', array('Фамилия')),
			),
			'phone' => array(
				'validation' => array('_validNotEmpty', array('Телефон')),
			),
			'country' => array(
				'validation' => array('_validNotEmpty'),
			),
			'region' => array(
				'validation' => array('_validNotEmpty', array('Область')),
			),
			'index' => array(
				'validation' => array('_validNotEmpty', array('Индекс')),
			),
			'city' => array(
				'validation' => array('_validNotEmpty', array('Город')),
			),
			'street' => array(
				'validation' => array('_validNotEmpty', array('Улица')),
			),
			'home' => array(
				'validation' => array('_validNotEmpty', array('Дом')),
			),
			'birthday' => array(
				'adapt' => '_adaptBirthday',
			),
		);
	}
}
