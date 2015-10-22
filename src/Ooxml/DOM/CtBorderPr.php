<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

/**
 * <xsd:complexType name="CT_BorderPr">
<xsd:sequence>
<xsd:element name="color" type="CT_Color" minOccurs="0" maxOccurs="1"/>
</xsd:sequence>
<xsd:attribute name="style" type="ST_BorderStyle" use="optional" default="none"/>
</xsd:complexType>
 *
 * Class CtBorderPr
 * @package YevgenGrytsay\Ooxml\DOM
 */
class CtBorderPr extends DOMElement
{
    const ATTR_STYLE = 'style';
    const COLOR = 'color';

    /**
     * start, end, top, bottom, diagonal, vertical, horizontal
     *
     * @var
     */
    protected $tag;
    /**
     * @var CtColor
     */
    protected $color;

    public function __construct($name, array $config)
    {
        parent::__construct($config);
        $this->tag = $name;

        if (array_key_exists(self::COLOR, $this->attributes)) {
            $this->color = new CtColor($this->getAttribute(self::COLOR));
            unset($this->attributes[self::COLOR]);
        }
    }

    protected function optional()
    {
        return array(self::ATTR_STYLE, self::COLOR);
    }

    protected function defaults()
    {
        return array(self::ATTR_STYLE => 'none');
    }

    protected function name()
    {
        return $this->tag;
    }

    public function render(\DOMDocument $doc)
    {
        $el = parent::render($doc);
        if ($this->color) {
            $colorEl = $this->color->render($doc);
            $el->appendChild($colorEl);
        }

        return $el;
    }

}