<?php
namespace interfaces;
interface IUserForAuthorization
{
	/* Start: User Info Methods */
	public function getLogin(); // return String
	public function getGroup(); // return String
	public function getStatus(); // return String
	public function getPassword(); // return String
	public function getUserData(); // return Array
	public function getUserName();// return String
	/*   End: User Info Methods */
	
	/* Start: Action Methods */
	public function editLogin($newLogin); // return Boolean
	public function editPassword($newPassword, $newPasswordConfirm); // return Boolean
	public function checkPassword($password); // return Boolean
	public function deleteUser(); // return Boolean
	public function delete(); // return Boolean
	/*   End: Action Methods */
}
