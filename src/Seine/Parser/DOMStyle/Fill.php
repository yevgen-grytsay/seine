<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 26.06.15
 * Time: 14:32
 */

namespace Seine\Parser\DOMStyle;


class Fill
{
    const PATTERN_SOLID = 'solid';
    const PATTERN_NONE = 'none';

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
     * @return mixed
     */
    public function getPatternType()
    {
        return $this->patternType;
    }

    /**
     * @param mixed $patternType
     *
     * @return $this
     */
    public function setPatternType($patternType)
    {
        $this->patternType = $patternType;

        return $this;
    }

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
     *
     * @return $this
     */
    public function setBgColor(Color $bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }
}