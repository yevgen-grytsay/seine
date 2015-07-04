<?php
/**
 * @author: yevgen
 */

namespace Seine\Writer\OfficeOpenXML2007;


use Seine\Parser\DOMStyle\StylesheetElement;

class IdResolver
{
    public function resolve(StylesheetElement $el)
    {
        return $el->getId();
    }
}