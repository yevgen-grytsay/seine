<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28.06.2015
 * Time: 14:29
 */

namespace Seine\Parser\DOMStyle;

/**
 * This element defines the properties for one of the fonts used in a workbook.
 *
 * Class Font
 * @package Seine\Parser\DOMStyle
 */
class Font extends StylesheetElement
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
     * @return $this
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
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
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
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
     *
     * @return $this
     */
    public function setBold($bold)
    {
        $this->bold = $bold;

        return $this;
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
     * @return $this
     */
    public function setItalic($italic)
    {
        $this->italic = $italic;

        return $this;
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
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
}