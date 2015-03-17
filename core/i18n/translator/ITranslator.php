<?php
namespace core\i18n\translator;
interface ITranslator
{
	public function __construct(ITranslateData $object);
	public function setCurrentLang(); // return $this
	public function setLang($lang); // return $this
	public function get($alias, $data = null); // return string
}