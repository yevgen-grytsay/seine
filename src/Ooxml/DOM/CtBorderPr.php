<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

use Symfony\Component\OptionsResolver\OptionsResolver;

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

    public static function createFromConfig($name, array $config = array())
    {
        $allowedNames = array(
            CtBorder::BORDER_START, CtBorder::BORDER_END, CtBorder::BORDER_TOP, CtBorder::BORDER_BOTTOM,
            CtBorder::BORDER_DIAGONAL, CtBorder::BORDER_VERTICAL, CtBorder::BORDER_HORIZONTAL, CtBorder::BORDER_LEFT,
            CtBorder::BORDER_RIGHT
        );
        if (!in_array($name, $allowedNames, true)) {
            throw new \RuntimeException(sprintf('Name "%s" is not allowed here.', $name));
        }

        try {
            $resolver = new OptionsResolver();
            $resolver->setDefined(array(
                self::ATTR_STYLE, self::COLOR
            ));
            $resolver->setDefaults(array(
                self::ATTR_STYLE => 'none'
            ));
            $attributes = $resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Can not create element from config', 0, $e);
        }
        $color = null;
        if (array_key_exists(self::COLOR, $attributes)) {
            $color = CtColor::createFromConfig($attributes[self::COLOR]);
            unset($attributes[self::COLOR]);
        }
        $el = new static($name, $attributes);
        if ($color) {
            $el->setColor($color);
        }

        return $el;
    }

    /**
     * @param CtColor $color
     */
    public function setColor(CtColor $color)
    {
        $this->childNodes = array($color);
    }
}