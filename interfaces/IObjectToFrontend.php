<?php
namespace interfaces;
interface IObjectToFrontend extends ISitemap
{
	/* Start: Meta Methods */
	public function getMetaTitle(); // return String
	public function getMetaDescription(); // return String
	public function getMetaKeywords(); // return String
	public function getHeaderText(); // return String
	/*   End: Meta Methods */
	
	/* Start: Main Data Methods */
	public function getName(); // return String
	/*   End: Main Data Methods */
	
	/* Start: URL Methods */
	public function getPath(); // return String (URL to Object Page)
	/*   End: URL Methods */
}