<?php
/**
 * Copyright (C) 2011 by Martin Vium
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Seine\Parser\DOM;

use Seine\Sheet;
use Seine\Writer;
use YevgenGrytsay\Ooxml\DOM\CtCol;
use YevgenGrytsay\Ooxml\StyleLookup;

final class DOMSheet extends DOMElement implements Sheet
{
    /**
     * @var Writer
     */
    private $writer;

    private $name = '';

    private $started = false;

    private $id;
    /**
     * @var \YevgenGrytsay\Ooxml\StyleLookup
     */
    private $styleLookup;
    /**
     * @var array
     */
    private $cols = array();

    /**
     * DOMSheet constructor.
     *
     * @param DOMFactory                       $factory
     * @param \Seine\Writer                    $writer
     * @param \YevgenGrytsay\Ooxml\StyleLookup $styleLookup
     */
    public function __construct(DOMFactory $factory, Writer $writer, StyleLookup $styleLookup)
    {
        parent::__construct($factory);
        $this->writer = $writer;
        $this->styleLookup = $styleLookup;
    }

    public function setColsConfig(array $config = array())
    {
        foreach ($config as $col) {
            $this->cols[] = new CtCol($col);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
        $this->setDefaultName();
    }

    private function setDefaultName()
    {
        if(! $this->getName()) {
            $this->setName('Sheet' . $this->getId());
        }
    }

    public function appendRow($data, array $config = array())
    {
        $this->startOnce();
        $this->writer->writeRow($this->getId(), $data, $config);
    }

    public function setName($name)
    {
        $this->name = (string)$name;
    }

    public function getName()
    {
        return $this->name;
    }

    private function startOnce()
    {
        if(! $this->writer) {
            throw new \Exception('writer is undefined');
        }

        if($this->started) {
            return;
        }

        $this->writer->startSheet($this, $this->styleLookup, $this->cols);
        $this->started = true;
    }

    public function close()
    {
        if($this->started) {
            $this->writer->endSheet($this);
        }

        $this->started = false;
    }}