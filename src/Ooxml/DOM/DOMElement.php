<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;


abstract class DOMElement implements ElementRendererInterface
{
    /**
     * @var string
     */
    protected $name;
    /**
    * @var array
    */
    protected $attributes = array();
    /**
     * @var ElementRendererInterface[]
     */
    protected $childNodes = array();

    /**
     * DOMElement constructor.
     *
     * @param string                                              $name
     * @param array                                               $attributes
     * @param \YevgenGrytsay\Ooxml\DOM\ElementRendererInterface[] $childNodes
     */
    protected function __construct($name, array $attributes, array $childNodes = array())
    {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->childNodes = $childNodes;
    }

    /**
     * @param \DOMDocument $doc
     *
     * @return \DOMElement
     * @throws \RuntimeException
     */
    public function render(\DOMDocument $doc)
    {
        $el = $doc->createElement($this->name);
        foreach ($this->attributes as $name => $value) {
            $el->setAttribute($name, $value);
        }

        foreach ($this->childNodes as $childNode) {
            $el->appendChild($childNode->render($doc));
        }

        return $el;
    }
}