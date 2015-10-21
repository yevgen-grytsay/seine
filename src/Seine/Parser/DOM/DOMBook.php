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

	/**
	 * @var DOMStylesheet[]
	 */
    private $styleSheetList = array();

    /**
     * DOMBook constructor.
     *
     * @param DOMFactory $factory
     */
    public function __construct(DOMFactory $factory)
    {
        parent::__construct($factory);

		$this->newStyleSheet();
    }

    /**
     * @return \Seine\Parser\DOM\DOMStylesheet
     */
    public function getDefaultStyleSheet()
    {
        return $this->styleSheetList[0];
    }

    public function setWriter(Writer $writer)
    {
        $this->writer = $writer;
    }

	/**
	 * Only one stylesheet supported so far.
	 * Thus this method was made private.
	 *
	 * @return DOMStylesheet
	 */
	private function newStyleSheet()
	{
		$sheet = new DOMStylesheet();
		$this->styleSheetList[] = $sheet;

		return $sheet;
	}
    
    public function addSheet(Sheet $sheet)
    {
        $sheet->setBook($this);
        $sheet->setId($this->sheetId++);
        $this->startBook();
        
        return $this->sheets[] = $sheet;
    }
    
    public function getSheets()
    {
        return $this->sheets;
    }

    /**
     * @return Sheet
     */
    public function getDefaultSheet()
    {
        if (!array_key_exists(0, $this->sheets)) {
            $this->addSheet(new DOMSheet($this->factory, $this->writer, $this->getDefaultStyleSheet()->getLookup()));
        }
        return $this->sheets[0];
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

    public function defineStyle(array $config = array())
    {
        return $this->getDefaultStyleSheet()->defineStyle($config);
    }

	/**
	 * @return DOMStylesheet[]
	 */
	public function getStyleSheetList()
	{
		return $this->styleSheetList;
	}
}