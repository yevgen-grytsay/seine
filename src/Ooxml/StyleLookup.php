<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml;


class StyleLookup
{
    /**
     * @var array
     */
    private $styleMap = array();

    /**
     * StyleLookup constructor.
     *
     * @param array $styleMap
     */
    public function __construct(array $styleMap)
    {
        $this->styleMap = $styleMap;
    }

    public function getStyleIdByReference($reference)
    {
        $hasStyle = $this->hasStyle($reference);
        if (!$hasStyle) {
            throw new \RuntimeException(sprintf('Style not found'));
        }

        return $this->styleMap[$reference];
    }

    public function hasStyle($reference)
    {
        return array_key_exists($reference, $this->styleMap);
    }
}