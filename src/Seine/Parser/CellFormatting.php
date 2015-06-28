<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 26.06.15
 * Time: 14:48
 */

namespace Seine\Parser;

use Seine\Parser\DOMStyle\Fill;
use Seine\Parser\DOMStyle\Font;
use Seine\Style;

class CellFormatting
{
    /**
     * @var Fill
     */
    private $fill;

	/**
	 * @var Font
	 */
	private $font;

	/**
     * @return Fill
     */
    public function getFill()
    {
        return $this->fill;
    }

    /**
     * @param Fill $fill
     */
    public function setFill(Fill $fill)
    {
        $this->fill = $fill;
    }

	/**
	 * @return Font
	 */
	public function getFont()
	{
		return $this->font;
	}

	/**
	 * @param Font $font
	 */
	public function setFont(Font $font)
	{
		$this->font = $font;
	}
}