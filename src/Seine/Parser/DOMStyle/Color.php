<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 26.06.15
 * Time: 14:33
 */

namespace Seine\Parser\DOMStyle;


class Color
{
    protected $rgb;

    /**
     * Color constructor.
     *
     * @param $rgb
     */
    public function __construct($rgb) { $this->rgb = $rgb; }

    /**
     * @return mixed
     */
    public function getRgb()
    {
        return $this->rgb;
    }

    /**
     * @param mixed $rgb
     */
    public function setRgb($rgb)
    {
        $this->rgb = $rgb;
    }

}