<?php 
namespace IntraworQ\Models;
/**
* 
*/
class User {
	protected $id;
	protected $name;
	protected $firstName;
	protected $description;

	public function __construct($name,$firstName,$description = null) {
		$this->name = $name;
		$this->firstName = $firstName;
		$this->description = $description;
		$this->id = rand(1000, 1);
	}

	public function getName() {
		return $this->name;
	}
	
	public function getFirstName() {
		return $this->firstName;
	}

	public function getDescription() {
		return $this->description;
	}

}

?>