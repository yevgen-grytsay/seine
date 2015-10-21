<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 28.06.2015
 * Time: 14:29
 */

namespace Seine\Parser\DOMStyle;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This element defines the properties for one of the fonts used in a workbook.
 *
 * Class Font
 * @package Seine\Parser\DOMStyle
 */
class Font
{
    const FAMILY_SWISS = 2;

    const CONFIG_COLOR = 'color';
    const CONFIG_SIZE = 'size';
    const CONFIG_BOLD = 'bold';

    private $family;
    private $size;
    private $bold = false;
    private $italic = false;

    /**
     * @var Color
     */
    private $color;

    /**
     * Font constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $config
     * @return Font
     */
    public static function createFromConfig(array $config = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            self::CONFIG_COLOR => null,
            self::CONFIG_SIZE => 24,
            self::CONFIG_BOLD => false
        ));
        $config = $resolver->resolve($config);

        $font = new static();
        if ($config[self::CONFIG_COLOR]) {
            $font->setColor(new Color($config[self::CONFIG_COLOR]));
        }

        if ($config[self::CONFIG_SIZE]) {
            $font->setSize($config[self::CONFIG_SIZE]);
        }

        if ($config[self::CONFIG_BOLD]) {
            $font->setBold($config[self::CONFIG_BOLD]);
        }

        return $font;
    }

    /**
     * @return mixed
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param mixed $family
     * @return $this
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isBold()
    {
        return $this->bold;
    }

    /**
     * @param boolean $bold
     *
     * @return $this
     */
    public function setBold($bold)
    {
        $this->bold = $bold;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isItalic()
    {
        return $this->italic;
    }

    /**
     * @param boolean $italic
     * @return $this
     */
    public function setItalic($italic)
    {
        $this->italic = $italic;

        return $this;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param Color $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
}