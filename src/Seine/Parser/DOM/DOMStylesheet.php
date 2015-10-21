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
use Seine\Parser\DOMStyle\NumberFormat;
use Seine\Parser\DOMStyle\PatternFill;

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
		$this->fills = new \SplObjectStorage();
		$this->colors = new \SplObjectStorage();
		$this->formats = new \SplObjectStorage();
		$this->styles = new \SplObjectStorage();
		$this->fonts = new \SplObjectStorage();
		$this->numberFormats = new \SplObjectStorage();

		$this->newPatternFill()->setPatternType(PatternFill::PATTERN_NONE);
		$this->newPatternFill()->setPatternType(PatternFill::PATTERN_GRAY_125);
		$this->newFont();
        $this->newFormatting();
	}

	public function defineStyle(array $config = array())
	{

	}
//
////	public function defineFromTemplate(StyleDefinition $template, array $config)
////	{
////
////	}

	/**
     * @return PatternFill
	 */
    public function newPatternFill()
	{
        $fill = $this->createPatternFill();
        $this->fills->attach($fill, $this->fills->count());

        return $fill;
	}

	/**
	 * @return PatternFill
	 */
    private function createPatternFill()
	{
        return new PatternFill();
	}

	/**
     * @return Font
	 */
    public function newFont()
	{
        $font = $this->createFont();
        $this->fonts->attach($font, $this->fonts->count());

        return $font;
	}

    /**
     * @return \Seine\Parser\DOMStyle\Font
     */
    private function createFont()
	{
        return new Font();
	}

	/**
     * @return CellFormatting
	 */
    public function newFormatting()
	{
        $format = $this->createFormatting();
        $this->formats->attach($format, $this->formats->count());

        return $format;
	}

    /**
     * @return \Seine\Parser\CellFormatting
     */
    private function createFormatting()
    {
        return new CellFormatting();
    }

    /**
     * @return Color
     */
    public function newColor()
	{
        $color = $this->createColor();
        $this->colors->attach($color, $this->colors->count());

        return $color;
	}

    /**
     * @param $formatString
     *
     * @return \Seine\Parser\DOMStyle\NumberFormat
     */
    public function newNumberFormat($formatString)
    {
        //TODO: обращение к числовому формату производится не через индекс, а через явно назначенный numFmtId
        $format = $this->createNumberFormat($formatString);
        $this->numberFormats->attach($format, $this->numberFormats->count());

        return $format;
    }

    private function createNumberFormat($formatString)
    {
        return new NumberFormat($formatString);
    }

    /**
     * @return \Seine\Parser\DOMStyle\Color
     */
    private function createColor()
	{
        return new Color();
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getFormats()
	{
		return $this->formats;
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
	public function getColors()
	{
		return $this->colors;
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getFonts()
	{
		return $this->fonts;
	}

    /**
     * @return \SplObjectStorage
     */
	public function getNumberFormats()
	{
		return $this->numberFormats;
	}
}