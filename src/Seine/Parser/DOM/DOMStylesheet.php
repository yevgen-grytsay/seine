<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28.06.2015
 * Time: 15:53
 */

namespace Seine\Parser\DOM;

use Seine\Parser\CellFormatting;
use Seine\Parser\DOMStyle\Color;
use Seine\Parser\DOMStyle\Font;
use Seine\Parser\DOMStyle\PatternFill;

class DOMStylesheet
{
	/**
	 * @var \ArrayObject
	 */
	private $formats;

	/**
	 * @var \ArrayObject
	 */
	private $fills;

	/**
	 * @var \ArrayObject
	 */
	private $colors;

	/**
	 * @var \ArrayObject
	 */
	private $fonts;

	/**
	 *
	 */
	public function __construct()
	{
		$this->fonts = new \ArrayObject();
		$this->fills = new \ArrayObject();
		$this->colors = new \ArrayObject();
		$this->formats = new \ArrayObject();
		$this->styles = new \ArrayObject();

        //TODO: move to default stylesheet template
		$this->newPatternFill()->setPatternType(PatternFill::PATTERN_NONE);
		$this->newPatternFill()->setPatternType(PatternFill::PATTERN_GRAY_125);
		$this->newFont();
        $this->newFormatting();
	}

	/**
     * @return PatternFill
	 */
    public function newPatternFill()
	{
		$id = $this->fills->count();
		$fill = new PatternFill($id);
		$this->fills->append($fill);

        return $fill;
	}

	/**
     * @return Font
	 */
    public function newFont()
	{
		$id = count($this->fonts);
		$font = new Font($id);
		$this->fonts->append($font);

        return $font;
	}

	/**
     * @return CellFormatting
	 */
    public function newFormatting()
	{
		$id = $this->formats->count();
        $format = new CellFormatting($id);
        $this->formats->append($format);

        return $format;
	}

    /**
     * @return Color
     */
    public function newColor()
	{
		$id = $this->colors->count();
        $color = new Color($id);
        $this->colors->append($color);

        return $color;
	}

	/**
	 * @return \Iterator
	 */
	public function getFormats()
	{
		return $this->formats->getIterator();
	}

	/**
	 * @return \Iterator
	 */
	public function getFills()
	{
		return $this->fills->getIterator();
	}

	/**
	 * @return \Iterator
	 */
	public function getColors()
	{
		return $this->colors->getIterator();
	}

	/**
	 * @return \Iterator
	 */
	public function getFonts()
	{
		return $this->fonts->getIterator();
	}
}