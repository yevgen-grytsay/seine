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
     * @var array
     */
    protected $childAttributes = array();
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
        $this->createResolver();
        $this->resolve($config);
    }

    /**
     * @param $config
     * @throws \RuntimeException
     */
    protected function resolve($config)
    {
        try {
            $this->attributes = $this->resolver->resolve($config);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to resolve config.', 0, $e);
        }
    }

    protected function createResolver()
    {
        $resolver = new OptionsResolver();
        try {
            $resolver->setDefined($this->getDefined());
            $resolver->setRequired($this->required());
            $resolver->setDefaults($this->defaults());
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to create resolver', 0, $e);
        }
        $this->resolver = $resolver;
    }

    /**
     * @return array
     */
    protected function getDefined()
    {
        $required = $this->required();
        $optional = $this->optional();
        $defaults = array_keys($this->defaults());
        $all = array_merge($required, $optional, $defaults);
        $map = array_fill_keys($all, null);
        $keys = array_keys($map);

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

    public function childAttributes()
    {
        return array();
    }

    /**
     * @param \DOMDocument $doc
     *
     * @return \DOMElement
     * @throws \RuntimeException
     */
    public function render(\DOMDocument $doc)
    {
        $el = $doc->createElement($this->name());
        foreach ($this->attributes as $name => $value) {
            $el->setAttribute($name, $value);
        }

        return $el;
    }

    abstract protected function name();

    /**
     * @return array
     */
    protected function required()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function defaults()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function optional()
    {
        return array();
    }
}