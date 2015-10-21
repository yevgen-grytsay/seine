<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\Sheet;

/**
 * Cell types are described in §18.18.11
 * "ECMA-376, Fourth Edition, Part 1 - Fundamentals And Markup Language Reference".
 *
 * Class Cell
 * @package YevgenGrytsay\Ooxml\Sheet
 */
class Cell
{
    /**
     * (Boolean) Cell containing a boolean.
     */
    const TYPE_BOOLEAN = 'b';
    /**
     * (Date) Cell contains a date in the ISO 8601 format.
     */
    const TYPE_DATE = 'd';
    /**
     * (Error) Cell containing an error.
     */
    const TYPE_ERROR = 'e';
    /**
     * (Inline String) Cell containing an (inline) rich string, i.e., one not in
     * the shared string table. If this cell type is used, then
     * the cell value is in the is element rather than the v
     * element in the cell (c element).
     */
    const TYPE_INLINE_STRING = 'inlineStr';
    /**
     * (Number) Cell containing a number.
     */
    const TYPE_NUMBER = 'n';
    /**
     * (Shared String) Cell containing a shared string.
     */
    const TYPE_SHARED_STRING = 's';
    /**
     * (String) Cell containing a formula string.
     */
    const TYPE_STRING = 'str';


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