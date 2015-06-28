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
namespace Seine;

use Seine\Parser\CellFormatting;
use Seine\Parser\CellStyle;
use Seine\Parser\DOMStyle\Color;
use Seine\Parser\DOMStyle\PatternFill;

interface Factory
{
    /**
     * @return WriterFactory
     */
    public function getWriterFactory();
    
    /**
     * @return Book
     */
    public function getBook();

    /**
     * @return Sheet
     */
    public function getSheet();

    /**
     * @return Row
     */
    public function getRow(array $cells);

    /**
     * @return CellStyle
     */
    public function getStyle();

	/**
	 * @param CellStyle $style
	 * @return CellFormatting
	 */
	public function getFormatting(CellStyle $style);

    /**
     * @return PatternFill
     */
    public function createPatternFill();

    /**
     * @return Color
     */
    public function createColor();

    /**
     * @param stream $fp
     * @param Configuration $config
     * @return Book
     */
    public function getConfiguredBook($fp, Configuration $config = null);

    /**
     * @param stream $fp
     * @param Configuration $config
     * @return Writer
     */
    public function getConfiguredWriter($fp, Configuration $config = null);

    /**
     * @param stream $fp
     * @param string $writerName
     * @return Writer
     */
    public function getWriterByName($fp, $writerName);
}