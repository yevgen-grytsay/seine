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
use Seine\Parser\DOMStyle\NumberFormat;
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
	 * @var NumberFormat
	 */
	private $numberFormat;

	/**
     * @return Fill
     */
    public function getFill()
    {
        return $this->fill;
    }

    /**
     * @param Fill $fill
     *
     * @return $this
     */
    public function setFill(Fill $fill)
    {
        $this->fill = $fill;

        return $this;
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
     *
     * @return $this
	 */
	public function setFont(Font $font)
	{
		$this->font = $font;

        return $this;
	}

	/**
	 * @return NumberFormat
	 */
	public function getNumberFormat()
	{
		return $this->numberFormat;
	}

	/**
	 * @param NumberFormat $numberFormat
	 */
	public function setNumberFormat(NumberFormat $numberFormat)
	{
		$this->numberFormat = $numberFormat;
	}
}