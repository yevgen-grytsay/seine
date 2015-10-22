<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 22.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;


interface ElementRendererInterface
{
    /**
     * @param \DOMDocument $doc
     *
     * @return \DOMElement
     */
    public function render(\DOMDocument $doc);
}