<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

/**
 * <xsd:complexType name="CT_Border">
<xsd:sequence>
<xsd:element name="start" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
<xsd:element name="end" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
<xsd:element name="top" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
<xsd:element name="bottom" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
<xsd:element name="diagonal" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
<xsd:element name="vertical" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
<xsd:element name="horizontal" type="CT_BorderPr" minOccurs="0" maxOccurs="1"/>
</xsd:sequence>
<xsd:attribute name="diagonalUp" type="xsd:boolean" use="optional"/>
<xsd:attribute name="diagonalDown" type="xsd:boolean" use="optional"/>
<xsd:attribute name="outline" type="xsd:boolean" use="optional" default="true"/>
</xsd:complexType>
 *
 * Class CtBorder
 * @package YevgenGrytsay\Ooxml\DOM
 */
class CtBorder extends DOMElement
{
    const ATTR_DIAGONAL_UP = 'diagonalUp';
    const ATTR_DIAGONAL_DOWN = 'diagonalDown';
    const ATTR_OUTLINE = 'outline';
    const ATTR_BORDER_PR_LIST = 'border_pr';

    const BORDER_LEFT = 'left';
    const BORDER_RIGHT = 'right';

    const BORDER_START = 'start';
    const BORDER_END = 'end';
    const BORDER_TOP = 'top';
    const BORDER_BOTTOM = 'bottom';
    const BORDER_DIAGONAL = 'diagonal';
    const BORDER_VERTICAL = 'vertical';
    const BORDER_HORIZONTAL = 'horizontal';

    private $borderNames = array(self::BORDER_START, self::BORDER_END, self::BORDER_TOP, self::BORDER_BOTTOM,
        self::BORDER_DIAGONAL, self::BORDER_VERTICAL, self::BORDER_HORIZONTAL, self::BORDER_LEFT, self::BORDER_RIGHT);

    /**
     * @var CtBorderPr[]
     */
    private $borders = array();

    public function __construct(array $config)
    {
        parent::__construct($config);
        foreach ($this->getAttribute(self::ATTR_BORDER_PR_LIST) as $name => $borderPr) {
            $border = new CtBorderPr($name, $borderPr);
            $this->borders[] = $border;
        }
        unset($this->attributes[self::ATTR_BORDER_PR_LIST]);
    }

    public function render(\DOMDocument $doc)
    {
        $el = parent::render($doc);
        foreach ($this->borders as $border) {
            $el->appendChild($border->render($doc));
        }

        return $el;
    }


    protected function defaults()
    {
        return array(self::ATTR_OUTLINE => true, self::ATTR_BORDER_PR_LIST => array());
    }

    protected function optional()
    {
        return array_merge(
            array(self::ATTR_DIAGONAL_UP, self::ATTR_DIAGONAL_DOWN, self::ATTR_OUTLINE, self::ATTR_BORDER_PR_LIST),
            $this->borderNames
        );
    }

    protected function name()
    {
        return 'border';
    }
}