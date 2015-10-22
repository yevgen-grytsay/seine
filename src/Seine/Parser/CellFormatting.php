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
use YevgenGrytsay\Ooxml\DOM\CtBorder;
use YevgenGrytsay\Ooxml\DOM\CtCellAlignment;

class CellFormatting
{
    const CONFIG_FONT = 'font';
	const CONFIG_FILL = 'fill';
	const CONFIG_NUMBER_FORMAT = 'numberFormat';
	const CONFIG_ALIGNMENT = 'alignment';
	const CONFIG_BORDER = 'border';

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
	 * @var CtCellAlignment
	 */
	private $align;
	/**
	 * @var CtBorder
	 */
	private $border;

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

	/**
	 * @return CtCellAlignment
	 */
	public function getAlign()
	{
		return $this->align;
	}

	/**
	 * @param CtCellAlignment $align
	 */
	public function setAlign(CtCellAlignment $align)
	{
		$this->align = $align;
	}

	/**
	 * @return CtBorder
	 */
	public function getBorder()
	{
		return $this->border;
	}

	/**
	 * @param CtBorder $border
	 */
	public function setBorder(CtBorder $border)
	{
		$this->border = $border;
	}

}