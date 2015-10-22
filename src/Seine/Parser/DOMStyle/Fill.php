<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 26.06.15
 * Time: 14:32
 */

namespace Seine\Parser\DOMStyle;


use Symfony\Component\OptionsResolver\OptionsResolver;

class Fill
{
    const CONFIG_PATTERN_TYPE = 'patternType';
    const CONFIG_BACK_COLOR = 'bgColor';
    const CONFIG_FORE_COLOR = 'fgColor';

    /**
     * @param array $config
     *
     * @return \Seine\Parser\DOMStyle\PatternFill
     * @throws \RuntimeException
     */
    public static function createFromConfig(array $config = array())
    {
        try {
            $resolver = new OptionsResolver();
            $resolver->setDefaults(array(
                self::CONFIG_PATTERN_TYPE => PatternFill::PATTERN_SOLID,
                self::CONFIG_BACK_COLOR => null,
                self::CONFIG_FORE_COLOR => null,
            ));
            $config = $resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not create fill element');
        }

        $foreColor = null;
        if ($config[self::CONFIG_FORE_COLOR]) {
            $foreColor = new Color($config[self::CONFIG_FORE_COLOR]);
        }
        $backColor = null;
        if ($config[self::CONFIG_BACK_COLOR]) {
            $backColor = new Color($config[self::CONFIG_BACK_COLOR]);
        }

        $fill = new PatternFill($config[self::CONFIG_PATTERN_TYPE], $foreColor, $backColor);

        return $fill;
    }
}