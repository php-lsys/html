<?php
namespace LSYS\Html\Form;
interface Data{
	/**
	 * @param string $key
	 * @return string
	 */
	public function get($key);
}