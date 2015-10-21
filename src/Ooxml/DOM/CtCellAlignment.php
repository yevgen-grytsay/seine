<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

/**
 * <xsd:complexType name="CT_CellAlignment">
<xsd:attribute name="horizontal" type="ST_HorizontalAlignment" use="optional"/>
<xsd:attribute name="vertical" type="ST_VerticalAlignment" use="optional"/>
<xsd:attribute name="textRotation" type="xsd:unsignedInt" use="optional"/>
<xsd:attribute name="wrapText" type="xsd:boolean" use="optional"/>
<xsd:attribute name="indent" type="xsd:unsignedInt" use="optional"/>
<xsd:attribute name="relativeIndent" type="xsd:int" use="optional"/>
<xsd:attribute name="justifyLastLine" type="xsd:boolean" use="optional"/>
<xsd:attribute name="shrinkToFit" type="xsd:boolean" use="optional"/>
<xsd:attribute name="readingOrder" type="xsd:unsignedInt" use="optional"/>
</xsd:complexType>
 *
 * <xsd:simpleType name="ST_HorizontalAlignment">
<xsd:restriction base="xsd:string">
<xsd:enumeration value="general"/>
<xsd:enumeration value="left"/>
<xsd:enumeration value="center"/>
<xsd:enumeration value="right"/>
<xsd:enumeration value="fill"/>
<xsd:enumeration value="justify"/>
<xsd:enumeration value="centerContinuous"/>
<xsd:enumeration value="distributed"/>
</xsd:restriction>
</xsd:simpleType>
<xsd:simpleType name="ST_VerticalAlignment">
<xsd:restriction base="xsd:string">
<xsd:enumeration value="top"/>
<xsd:enumeration value="center"/>
<xsd:enumeration value="bottom"/>
<xsd:enumeration value="justify"/>
<xsd:enumeration value="distributed"/>
</xsd:restriction>
</xsd:simpleType>
 *
 * Class CtCellAlignment
 * @package YevgenGrytsay\Ooxml\DOM
 */
class CtCellAlignment extends DOMElement
{
    const ATTR_HORIZONTAL = 'horizontal';

    const HOR_GENERAL = 'general';
    const HOR_LEFT = 'left';
    const HOR_CENTER = 'center';
    const HOR_RIGHT = 'right';
    const HOR_FILL = 'fill';
    const HOR_JUSTIFY = 'justify';

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->resolver->setAllowedValues(self::ATTR_HORIZONTAL, array(
            self::HOR_GENERAL, self::HOR_LEFT, self::HOR_CENTER,
            self::HOR_RIGHT, self::HOR_FILL, self::HOR_JUSTIFY
        ));
    }

    protected function optional()
    {
        return array(self::ATTR_HORIZONTAL);
    }


    protected function name()
    {
        return 'alignment';
    }
}
