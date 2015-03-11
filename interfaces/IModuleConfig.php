<?php
namespace interfaces;
interface IModuleConfig
{
	/* Start: Model Methods */
	public function setModelData(&$data); // return $this
	public function setModelErrors(&$errors); // return $this
	public function setModelErrorList(&$errorsList); // return $this
	/*   End: Model Methods */
	
	/* Start: MainTable Methods */
	public function mainTable(); // return String
	public function removePostfix(); // return $this
	/*   End: MainTable Methods */
	
	/* Start: Fields Rules Methods */
	public function rules(); // return RulesArray
	public function outputRules(); // return RulesArray
	/*   End: Fields Rules Methods */
	
	/* Start: Errors Methods */
	public function setError($key, $text); // return $this
	public function addError($key, $value); // return $this
	/*   End: Errors Methods */
	
	/* Start: Fields Config Methods */
	public function getObjectFields(); // return Array
	public function getIdField(); // return Int/Null
	public function getRemovedStatus(); // return Int
	public function getParentConfig(); // return ParentConfig Object
	/*   End: Fields Config Methods */
	
	/* Start: Signature Methods */
	public function getObjectClass(); // return String
	public function getObjectsClass(); // return String
	public function getNewObjects(); // return ModuleObjects
	/*   End: Signature Methods */
	
	/* Start: Templates Methods */
	public function getAdminTemplateDir(); // return String
	public function getImagesPath(); // return String
	/*   End: Templates Methods */
}