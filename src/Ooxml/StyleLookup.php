<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml;


class StyleLookup
{
    /**
     * @var \SplObjectStorage
     */
    private $styleMap;

    /**
     * StyleLookup constructor.
     *
     * @param \ArrayObject $styleMap
     */
    public function __construct(\ArrayObject $styleMap)
    {
        $this->styleMap = $styleMap;
    }

    /**
     * @param $reference
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function getStyleIdByReference($reference)
    {
        $hasStyle = $this->hasStyle($reference);
        if (!$hasStyle) {
            throw new \RuntimeException(sprintf('Style not found'));
        }

        return $reference;
    }

    /**
     * @param $reference
     *
     * @return bool
     */
    public function hasStyle($reference)
    {
        return $this->styleMap->offsetExists($reference);
    }
}