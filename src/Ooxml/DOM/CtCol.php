<?php
namespace YevgenGrytsay\Ooxml\DOM;

/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 18.3.1.13
 * page 1593
 *
 * Class CtCol
 * @package YevgenGrytsay\Ooxml\DOM
 */
class CtCol extends DOMElement
{
    /**
     * min (Minimum Column) First column affected by this 'column info' record.
     */
    const ATTR_MIN = 'min';
    /**
     * max (Maximum Column) Last column affected by this 'column info' record.
     */
    const ATTR_MAX = 'max';
    /**
     * Flag indicating if the specified column(s) is set to 'best fit'. 'Best fit' is set to true under
     *    these conditions:
     * - The column width has never been manually set by the user, AND
     * - The column width is not the default width
     * -
     * - 'Best fit' means that when numbers are typed into a cell contained in a 'best fit'
     * column, the column width should automatically resize to display the number.
     * [Note: In best fit cases, column width must not be made smaller, only larger. end
     * note]
     */
    const ATTR_BEST_FIT = 'bestFit';
    /**
     * Column width measured as the number of characters of the maximum digit width of the
     * numbers 0, 1, 2, ..., 9 as rendered in the normal style's font. There are 4 pixels of margin
     * padding (two on each side), plus 1 pixel padding for the gridlines.
     */
    const ATTR_WIDTH = 'width';
    /**
     * Flag indicating that the column width for the affected column(s) is different from the
     * default or has been manually set.
     */
    const ATTR_CUSTOM_WIDTH = 'customWidth';

    /**
     * CtCol constructor.
     *
     * @param array $attributes
     * @param array $childNodes
     */
    protected function __construct(array $attributes, array $childNodes = array())
    {
        parent::__construct('col', $attributes, $childNodes);
    }

    /**
     * @param array $config
     *
     * @return static
     * @throws \RuntimeException
     */
    public static function createFromConfig(array $config = array())
    {
        try {
            $resolver = new OptionsResolver();
            $resolver->setRequired(array(self::ATTR_MIN, self::ATTR_MAX));
            $resolver->setDefaults(array(
                self::ATTR_BEST_FIT => false,
                self::ATTR_WIDTH => null,
                self::ATTR_CUSTOM_WIDTH => false
            ));
            $attributes = $resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Can not create element from config', 0, $e);
        }
        $el = new static($attributes);

        return $el;
    }
}
/**
 * "ECMA-376, Fourth Edition, Part 1 - Fundamentals And Markup Language Reference"
 * page 3901
 */
//<xsd:complexType name="CT_Col">
//<xsd:attribute name="min" type="xsd:unsignedInt" use="required"/>
//<xsd:attribute name="max" type="xsd:unsignedInt" use="required"/>
//<xsd:attribute name="width" type="xsd:double" use="optional"/>
//<xsd:attribute name="style" type="xsd:unsignedInt" use="optional" default="0"/>
//<xsd:attribute name="hidden" type="xsd:boolean" use="optional" default="false"/>
//<xsd:attribute name="bestFit" type="xsd:boolean" use="optional" default="false"/>
//<xsd:attribute name="customWidth" type="xsd:boolean" use="optional" default="false"/>
//<xsd:attribute name="phonetic" type="xsd:boolean" use="optional" default="false"/>
//<xsd:attribute name="outlineLevel" type="xsd:unsignedByte" use="optional" default="0"/>
//<xsd:attribute name="collapsed" type="xsd:boolean" use="optional" default="false"/>
//</xsd:complexType>