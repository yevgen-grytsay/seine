<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 26.06.15
 * Time: 14:48
 */

namespace Seine\Parser;

use Seine\Parser\DOMStyle\Fill;
use Seine\Style;

class CellFormatting implements Style
{
    /**
     * @var Fill
     */
    private $fill;

	/**
	 * @var CellStyle
	 */
	private $style;

	public function __construct(CellStyle $style)
	{
		$this->style = $style;
	}

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
     * @internal assigned by Book
     * @access   private
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getFontBold()
    {
        // TODO: Implement getFontBold() method.
    }

    public function getFontFamily()
    {
        // TODO: Implement getFontFamily() method.
    }

    public function getFontSize()
    {
        // TODO: Implement getFontSize() method.
    }
}