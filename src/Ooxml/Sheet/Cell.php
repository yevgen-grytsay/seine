<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\Sheet;


class Cell
{
    /**
     * @var mixed
     */
    private $styleReference;
    /**
     * @var mixed
     */
    private $value;

    /**
     * Cell constructor.
     *
     * @param mixed $value
     * @param mixed $styleReference
     */
    public function __construct($value, $styleReference = null)
    {
        $this->value = $value;
        $this->styleReference = $styleReference;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getStyleReference()
    {
        return $this->styleReference;
    }

}