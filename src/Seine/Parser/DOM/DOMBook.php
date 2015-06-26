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

use Seine\Book;
use Seine\Factory;
use Seine\Parser\DOMStyle\Fill;
use Seine\Sheet;
use Seine\Writer;

final class DOMBook extends DOMElement implements Book
{
    private $sheetId = 1;
    private $sheets = array();
    
    /**
     * @var Writer
     */
    private $writer;
   
    private $started = false;

    private $styleId = 0;

    /**
     * @var \SplObjectStorage
     */
    private $styles;

    /**
     * @var \SplObjectStorage
     */
    private $fills;

    /**
     * @var \SplObjectStorage
     */
    private $colors;

    /**
     * DOMBook constructor.
     *
     * @param \Seine\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->fills = new \SplObjectStorage();
        $this->colors = new \SplObjectStorage();
        $this->styles = new \SplObjectStorage();
        parent::__construct($factory);

        $this->newFill()->setPatternType(Fill::PATTERN_NONE);
    }

    public function setWriter(Writer $writer)
    {
        $this->writer = $writer;
    }
    
    public function newSheet($name = null)
    {
        $sheet = $this->factory->getSheet();
        if($name) {
            $sheet->setName($name);
        }

        $this->addSheet($sheet);
        return $sheet;
    }
    
    public function addSheet(Sheet $sheet)
    {
        $sheet->setBook($this);
        $sheet->setId($this->sheetId++);
        $this->startBook();
        
        $sheet->setWriter($this->writer);
        
        return $this->sheets[] = $sheet;
    }
    
    public function getSheets()
    {
        return $this->sheets;
    }
    
    public function newStyle()
    {
        $style = $this->factory->getStyle();
        $this->styles->attach($style, $this->styles->count());

        return $style;
    }

    /**
     * @return Fill
     */
    public function newFill()
    {
        $fill = $this->factory->createFill();
        $this->fills->attach($fill, $this->fills->count());

        return $fill;
    }

    public function newColor()
    {
        $color = $this->factory->createColor();
        $this->colors->attach($color, $this->colors->count());

        return $color;
    }
    
    public function getStyles()
    {
        return $this->styles;
    }
    
    private function startBook()
    {
        if(! $this->writer) {
            throw new \Exception('writer is undefined');
        }
        
        if($this->started) {
            return;
        }
        
        $this->writer->startBook($this);
        $this->started = true;
    }
    
    public function close()
    {
        foreach($this->getSheets() as $sheet) {
            $sheet->close();
        }

        if($this->started) {
            $this->writer->endBook($this);
        }
        
        $this->started = false;
    }

    /**
     * @return array
     */
    public function getFills()
    {
        return $this->fills;
    }

    /**
     * @return array
     */
    public function getColors()
    {
        return $this->colors;
    }
}