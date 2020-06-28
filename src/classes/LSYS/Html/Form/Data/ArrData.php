<?php
namespace LSYS\Html\Form\Data;
use LSYS\Html\Form\Data;
class ArrData implements Data{
	private $_arr;
	public function __construct(array &$array){
		$this->_arr=&$array;
	}
	/**
	 * @param string $key
	 * @return string
	 */
	public function get(string $key):?string {
		if (isset($this->_arr[$key])) return $this->_arr[$key];
		return null;
	}
}