<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;

use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /**
     * @param array $config
     *
     * @return \YevgenGrytsay\Ooxml\DOM\CtBorder
     * @throws \RuntimeException
     */
    public static function createFromConfig(array $config = array())
    {
        try {
            $resolver = new OptionsResolver();
            $resolver->setDefaults(array(
                    self::ATTR_OUTLINE => true,
                    self::ATTR_BORDER_PR_LIST => array())
            );
            $resolver->setDefined(array_merge(
                array(self::ATTR_DIAGONAL_UP, self::ATTR_DIAGONAL_DOWN, self::ATTR_OUTLINE, self::ATTR_BORDER_PR_LIST)
            ));
            $attributes = $resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Can not create element from config', 0, $e);
        }

        /**
         * BorderPr
         */
        $borders = array();
        foreach ($attributes[self::ATTR_BORDER_PR_LIST] as $name => $borderPr) {
            $color = null;
            if (array_key_exists(CtBorderPr::COLOR, $borderPr)) {
                $color = CtColor::createFromConfig($borderPr[CtBorderPr::COLOR]);
                unset($borderPr[CtBorderPr::COLOR]);
            }
            $border = CtBorderPr::createFromConfig($name, $borderPr);
            if ($color) {
                $border->setColor($color);
            }
            $borders[] = $border;
        }
        unset($attributes[self::ATTR_BORDER_PR_LIST]);
        $el = new CtBorder($attributes, $borders);

        return $el;
    }

    /**
     * CtBorder constructor.
     *
     * @param array $attributes
     * @param array $childNodes
     */
    protected function __construct(array $attributes, array $childNodes = array())
    {
        parent::__construct('border', $attributes, $childNodes);
    }
}