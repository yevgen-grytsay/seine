<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 01.07.15
 * Time: 12:17
 */

namespace Seine\Parser\DOM;


use Seine\Parser\CellFormatting;

class DOMCell
{
    /**
     * @var CellFormatting
     */
    private $formatting;

    private $value;

    /**
     * @return CellFormatting
     */
    public function getFormatting()
    {
        return $this->formatting;
    }

    /**
     * @param CellFormatting $format
     *
     * @return DOMCell
     */
    public function setFormatting(CellFormatting $format)
    {
        $this->formatting = $format;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return DOMCell
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}