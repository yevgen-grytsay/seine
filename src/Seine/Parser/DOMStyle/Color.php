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
     * @return mixed
     */
    public function getRgb()
    {
        return $this->rgb;
    }

    /**
     * @param mixed $rgb
     * @return Color
     */
    public function setRgb($rgb)
    {
        $this->rgb = $rgb;

        return $this;
    }
}