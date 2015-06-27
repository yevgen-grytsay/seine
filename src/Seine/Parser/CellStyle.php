<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 27.06.2015
 * Time: 10:36
 */

namespace Seine\Parser;


class CellStyle
{
	private $fill;

	/**
	 * @return mixed
	 */
	public function getFill() {
		return $this->fill;
	}

	/**
	 * @param mixed $fill
	 */
	public function setFill($fill) {
		$this->fill = $fill;
	}
}