<?php namespace IntraworQ\Models;
/**
* 
*/
class User extends Model {

	protected $id;
	protected $name;

	function __construct($name) {
		$this->name = $name;
		$this->id = rand(1000, 1);
	}

	function getName() {
		return $this->name;
	}
}

?>