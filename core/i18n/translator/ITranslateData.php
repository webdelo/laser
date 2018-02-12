<?php
namespace core\i18n\translator;
interface ITranslateData
{
	public function getPattern($alias, $lang);
}