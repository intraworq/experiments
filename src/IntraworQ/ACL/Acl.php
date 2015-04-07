<?php

namespace IntraworQ\ACL;

use Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl {

	/**
	 * ustawiamy definicję dostępnych ról
	 */
	public function __construct() {
		// APPLICATION ROLES
		$this->addRole('guest');
		// member role "extends" guest, meaning the member role will get all of
		// the guest role permissions by default
		$this->addRole('member', 'guest');
		$this->addRole('workflow', 'guest');
		$this->addRole('admin');


	}

	/**
	 * ustawiamy uprawnienia
	 */
	public function setPerm() {
		// APPLICATION PERMISSIONS
		// Now we allow or deny a role's access to resources. The third argument
		// is 'privilege'. We're using HTTP method for resources.
		$this->allow('guest', '/', 'GET');
		$this->allow('guest', '/login', array('GET', 'POST'));
		$this->allow('guest', '/logout', 'GET');
		$this->allow('guest', '/guest', array('GET', 'edit', 'menu1'));
		$this->allow('workflow', '/param/:name', array('GET', 'edit'));
		$this->deny('guest', '/guest', array('edit'));
		$this->allow('workflow', '/guest', array('edit'));
		$this->allow('admin');
		$this->deny('admin', '/requestAjax');
	}

}
