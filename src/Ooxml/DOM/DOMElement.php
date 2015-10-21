<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 21.10.15
 */

namespace YevgenGrytsay\Ooxml\DOM;


use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class DOMElement
{
    /**
    * @var array
    */
    protected $attributes = array();
    /**
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * CtCol constructor.
     *
     * @param array $config
     * @throws \RuntimeException
     */
    public function __construct(array $config = array())
    {
        $this->resolver = $resolver = new OptionsResolver();
        try {
//            $resolver->setDefined($this->getDefined());
            $resolver->setRequired($this->required());
            $resolver->setDefaults($this->defaults());
            $this->attributes = $resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to create element "CtCol" from config.', 0, $e);
        }
    }

    /**
     * @return array
     */
    protected function getDefined()
    {
        $required = $this->required;
        $defaults = array_keys($this->defaults);
        $map = array_fill_keys($required + $defaults, null);
        $keys = array_flip($map);

        return $keys;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \RuntimeException Если атрибут не существует.
     */
    public function getAttribute($name)
    {
        if (!array_key_exists($name, $this->attributes)) {
            throw new \RuntimeException(sprintf('Unknown attribute "%s".', $name));
        }
        return $this->attributes[$name];
    }

    /**
     * @param \DOMDocument $doc
     *
     * @return \DOMElement
     * @throws \RuntimeException
     */
    public function render(\DOMDocument $doc)
    {
        $definedOptions = $this->resolver->getDefinedOptions();
        $el = $doc->createElement('col');
        foreach ($definedOptions as $name) {
            $el->setAttribute($name, $this->getAttribute($name));
        }

        return $el;
    }

    /**
     * @return array
     */
    abstract protected function required();
    /**
     * @return array
     */
    abstract protected function defaults();
}