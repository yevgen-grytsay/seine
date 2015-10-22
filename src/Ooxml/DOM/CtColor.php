<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 22.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

/**
 * <xsd:complexType name="CT_Color">
<xsd:attribute name="auto" type="xsd:boolean" use="optional"/>
<xsd:attribute name="indexed" type="xsd:unsignedInt" use="optional"/>
<xsd:attribute name="rgb" type="ST_UnsignedIntHex" use="optional"/>
<xsd:attribute name="theme" type="xsd:unsignedInt" use="optional"/>
<xsd:attribute name="tint" type="xsd:double" use="optional" default="0.0"/>
</xsd:complexType>
 *
 * Class CtColor
 * @package YevgenGrytsay\Ooxml\DOM
 */
class CtColor extends DOMElement
{
    const ATTR_AUTO = 'auto';
    const ATTR_INDEXED = 'indexed';
    const ATTR_RGB = 'rgb';
    const ATTR_THEME = 'theme';
    const ATTR_TINT = 'tint';

//    protected function defaults()
//    {
//        return array(self::ATTR_TINT => 0.0);
//    }

    protected function optional()
    {
        return array(self::ATTR_AUTO, self::ATTR_INDEXED, self::ATTR_RGB, self::ATTR_THEME, self::ATTR_TINT);
    }

    protected function name()
    {
        return 'color';
    }
}