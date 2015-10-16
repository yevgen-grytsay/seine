<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 16.10.15
 */

namespace Seine\Parser\DOMStyle;


class NumberFormat
{
    /**
     * @var string
     */
    private $formatCode;

    /**
     * NumberFormat constructor.
     *
     * @param string $formatCode
     */
    public function __construct($formatCode)
    {
        $this->formatCode = $formatCode;
    }

    /**
     * @return string
     */
    public function getFormatCode()
    {
        return $this->formatCode;
    }
}