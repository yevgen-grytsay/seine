<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

use Symfony\Component\OptionsResolver\OptionsResolver;

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
    const ATTR_VERTICAL = 'vertical';
    const ATTR_WRAP_TEXT = 'wrapText';

    const HOR_GENERAL = 'general';
    const HOR_LEFT = 'left';
    const HOR_CENTER = 'center';
    const HOR_RIGHT = 'right';
    const HOR_FILL = 'fill';
    const HOR_JUSTIFY = 'justify';

    const VERT_TOP = 'top';
    const VERT_CENTER = 'center';
    const VERT_BOTTOM = 'bottom';
    const VERT_JUSTIFY = 'justify';
    const VERT_DISTRIBUTED = 'distributed';

    /**
     * @param array $config
     *
     * @return \YevgenGrytsay\Ooxml\DOM\CtCellAlignment
     * @throws \RuntimeException
     */
    public static function createFromConfig(array $config = array())
    {
        try {
            $resolver = new OptionsResolver();
            $resolver->setDefined(array_merge(
                array(self::ATTR_HORIZONTAL, self::ATTR_VERTICAL, self::ATTR_WRAP_TEXT)
            ));
            $resolver->setAllowedValues(self::ATTR_HORIZONTAL, array(
                self::HOR_GENERAL, self::HOR_LEFT, self::HOR_CENTER,
                self::HOR_RIGHT, self::HOR_FILL, self::HOR_JUSTIFY
            ));
            $resolver->setAllowedValues(self::ATTR_VERTICAL, array(
                self::VERT_TOP, self::VERT_CENTER, self::VERT_BOTTOM,
                self::VERT_JUSTIFY, self::VERT_DISTRIBUTED
            ));
            $resolver->setAllowedValues(self::ATTR_WRAP_TEXT, array('true', 'false'));
            $attributes = $resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Can not create element from config', 0, $e);
        }
        $el = new CtCellAlignment($attributes);

        return $el;
    }

    /**
     * CtCellAlignment constructor.
     *
     * @param array $attributes
     * @param array $childNodes
     */
    public function __construct(array $attributes, array $childNodes = array())
    {
        parent::__construct('alignment', $attributes, $childNodes);
    }

}
