<?php
namespace IntraworQ\Library;

use Zend\Authentication\Storage\Session as ZendSessionStorage;

class Session extends ZendSessionStorage {


    public function add($key,$value)
    {
        $this->session->$key = $value;
    }

	public function get($key)
    {
        return $this->session->$key;
    }
}