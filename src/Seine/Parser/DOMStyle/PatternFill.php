<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28.06.2015
 * Time: 14:51
 */

namespace Seine\Parser\DOMStyle;


class PatternFill implements Fill
{
	const PATTERN_SOLID = 'solid';
	const PATTERN_NONE = 'none';
	const PATTERN_MEDIUM_GRAY = 'mediumGray';
	const PATTERN_DARK_GRAY = 'darkGray';
	const PATTERN_LIGHT_GRAY = 'lightGray';
	const PATTERN_DARK_HORIZONTAL = 'darkHorizontal';
	const PATTERN_DARK_VERTICAL = 'darkVertical';
	const PATTERN_DARK_DOWN = 'darkDown';
	const PATTERN_DARK_UP = 'darkUp';
	const PATTERN_DARK_GRID = 'darkGrid';
	const PATTERN_DARK_TRELLIS = 'darkTrellis';
	const PATTERN_LIGHT_HORIZONTAL = 'lightHorizontal';
	const PATTERN_LIGHT_VERTICAL = 'lightVertical';
	const PATTERN_LIGHT_DOWN = 'lightDown';
	const PATTERN_LIGHT_UP = 'lightUp';
	const PATTERN_LIGHT_GRID = 'lightGrid';
	const PATTERN_LIGHT_TRELLIS = 'lightTrellis';
	const PATTERN_GRAY_125 = 'gray125';
	const PATTERN_GRAY_0625 = 'gray0625';

	/**
	 * @var
	 */
	protected $patternType = self::PATTERN_SOLID;

	/**
	 * @var Color
	 */
	protected $fgColor;

	/**
	 * @var Color
	 */
	protected $bgColor;

	/**
	 * @return Color
	 */
	public function getFgColor()
	{
		return $this->fgColor;
	}

	/**
	 * @param Color $fgColor
	 *
	 * @return $this
	 */
	public function setFgColor(Color $fgColor)
	{
		$this->fgColor = $fgColor;

		return $this;
	}

	/**
	 * @return Color
	 */
	public function getBgColor()
	{
		return $this->bgColor;
	}

	/**
	 * @param Color $bgColor
	 */
	public function setBgColor($bgColor)
	{
		$this->bgColor = $bgColor;
	}

	/**
	 * @return mixed
	 */
	public function getPatternType()
	{
		return $this->patternType;
	}

	/**
	 * @param mixed $patternType
	 */
	public function setPatternType($patternType)
	{
		$this->patternType = $patternType;
	}
}