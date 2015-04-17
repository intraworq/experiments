<?php
namespace IntraworQ\Library;

use Zend\Authentication\Storage\Session as ZendSessionStorage;

class Session extends ZendSessionStorage {


	/**
	 * Add value to session
	 * @param string $key
	 * @param mix $value
	 */
    public function add($key,$value)
    {
		$sessionData = $this->read();
		$sessionData[$key] = $value;
		$this->write($sessionData);
    }

	/**
	 * Get value from session
	 * @param string $key
	 * @return mix
	 */
	public function get($key)
    {
        $sessionData = $this->read();
		if (array_key_exists($key, $sessionData)) {
			return $sessionData[$key];
		} else {
			return null;
		}
    }
}