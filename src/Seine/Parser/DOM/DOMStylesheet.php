<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28.06.2015
 * Time: 15:53
 */

namespace Seine\Parser\DOM;

use Seine\Parser\CellFormatting;
use Seine\Parser\DOMStyle\Fill;
use Seine\Parser\DOMStyle\Font;
use Seine\Parser\DOMStyle\NumberFormat;
use Seine\Parser\DOMStyle\PatternFill;
use YevgenGrytsay\Ooxml\DOM\CtCellAlignment;
use YevgenGrytsay\Ooxml\StyleLookup;

class DOMStylesheet
{
	/**
	 * @var \SplObjectStorage
	 */
	private $formats;

	/**
	 * @var \SplObjectStorage
	 */
	private $fills;

	/**
	 * @var \SplObjectStorage
	 */
	private $colors;

	/**
	 * @var \SplObjectStorage
	 */
	private $fonts;
	/**
	 * @var \SplObjectStorage
	 */
	private $numberFormats;

	/**
	 *
	 */
	public function __construct()
	{
//		$this->fills = new \SplObjectStorage();
//		$this->colors = new \SplObjectStorage();
//		$this->formats = new \SplObjectStorage();
//		$this->fonts = new \SplObjectStorage();
		$this->numberFormats = new \SplObjectStorage();

		$this->fills = new \SplObjectStorage();
		$this->fills->attach(new PatternFill(PatternFill::PATTERN_NONE), $this->fills->count());
		$this->fills->attach(new PatternFill(PatternFill::PATTERN_GRAY_125), $this->fills->count());

		$this->fonts = new \SplObjectStorage();
		$this->fonts->attach(new Font(), $this->fonts->count());

		$this->styles = new \ArrayObject();
		$this->styles[] = new CellFormatting();

		$this->defineStyle(array());
	}

	/**
	 * @param array $config
	 *
	 * @return int
	 */
	public function defineStyle(array $config = array())
	{
		$style = new CellFormatting();
		foreach ($config as $key => $value) {
			switch($key) {
				case CellFormatting::CONFIG_FONT:
					$font = $this->createFont($value);
					$style->setFont($font);
					break;

				case CellFormatting::CONFIG_FILL:
					$fill = $this->createFill($value);
					$style->setFill($fill);
					break;

				case CellFormatting::CONFIG_NUMBER_FORMAT:
					$format = $this->createNumberFormat($value);
					$style->setNumberFormat($format);
					break;

				case CellFormatting::CONFIG_ALIGNMENT:
					$align = $this->createAlignment($value);
					$style->setAlign($align);
					break;
			}
		}
		$this->styles[] = $style;

		return count($this->styles) - 1;
	}

	private function createAlignment(array $config = array())
	{
		$align = new CtCellAlignment($config);

		return $align;
	}

	private function createNumberFormat(array $config = array())
	{
		$format = NumberFormat::createFromConfig($config);
		$this->numberFormats->attach($format, $this->numberFormats->count());

		return $format;
	}

	private function createFont(array $config = array())
	{
		$font = Font::createFromConfig($config);
		$this->fonts->attach($font, $this->fonts->count());

		return $font;
	}

	private function createFill(array $config = array())
	{
		$fill = Fill::createFromConfig($config);
		$this->fills->attach($fill, $this->fills->count());

		return $fill;
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getFonts()
	{
		return $this->fonts;
	}

	/**
	 * @return CellFormatting[]
	 */
	public function getStyles()
	{
		return $this->styles;
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getFills()
	{
		return $this->fills;
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getNumberFormats()
	{
		return $this->numberFormats;
	}

	public function getLookup()
	{
		return new StyleLookup($this->styles);
	}
}