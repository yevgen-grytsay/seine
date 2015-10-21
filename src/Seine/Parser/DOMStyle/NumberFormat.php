<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 16.10.15
 */

namespace Seine\Parser\DOMStyle;


use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberFormat
{
    const CONFIG_FORMAT_CODE = 'formatCode';

    /**
     * @var string
     */
    private $formatCode;

    /**
     * @param array $config
     *
     * @return static
     */
    public static function createFromConfig(array $config = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            self::CONFIG_FORMAT_CODE => null,
        ));
        $config = $resolver->resolve($config);

        return new static($config[self::CONFIG_FORMAT_CODE]);
    }
    /**
     * NumberFormat constructor.
     *
     * @param string $formatCode
     */
    public function __construct($formatCode)
    {
        $this->formatCode = $formatCode;
    }

    /**
     * @return string
     */
    public function getFormatCode()
    {
        return $this->formatCode;
    }
}