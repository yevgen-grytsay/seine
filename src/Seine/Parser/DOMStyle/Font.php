<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28.06.2015
 * Time: 14:29
 */

namespace Seine\Parser\DOMStyle;


class Font
{
	const FAMILY_SWISS = 2;

	private $family;
	private $size;
	private $bold = false;
	private $italic = false;

	/**
	 * @var Color
	 */
	private $color;

	/**
	 * @return mixed
	 */
	public function getFamily()
	{
		return $this->family;
	}

	/**
	 * @param mixed $family
	 */
	public function setFamily($family)
	{
		$this->family = $family;
	}

	/**
	 * @return mixed
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param mixed $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}

	/**
	 * @return boolean
	 */
	public function isBold()
	{
		return $this->bold;
	}

	/**
	 * @param boolean $bold
	 */
	public function setBold($bold)
	{
		$this->bold = $bold;
	}

	/**
	 * @return boolean
	 */
	public function isItalic()
	{
		return $this->italic;
	}

	/**
	 * @param boolean $italic
	 */
	public function setItalic($italic)
	{
		$this->italic = $italic;
	}

	/**
	 * @return Color
	 */
	public function getColor()
	{
		return $this->color;
	}

	/**
	 * @param Color $color
	 */
	public function setColor($color)
	{
		$this->color = $color;
	}
}