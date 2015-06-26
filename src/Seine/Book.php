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

use Seine\Parser\CellStyle;
use Seine\Parser\DOMStyle\Color;
use Seine\Parser\DOMStyle\Fill;

/**
 * Represents the entire book or document.
 */
interface Book
{
    /**
     * Add a Sheet or new subset of data to the Book.
     * 
     * @param Sheet $sheet
     * @return Sheet
     */
    public function addSheet(Sheet $sheet);
    
    /**
     * Get the Sheet(s) added to this Book.
     * 
     * @return Sheet[]
     */
    public function getSheets();

    /**
     * Create and add a new Sheet on the Book.
     *
     * @param string $name
     * @return Sheet
     */
    public function newSheet($name = null);
    
    /**
     * Create and add a new Style for this Book. Only needs to be added to the wanted Row(s).
     * 
     * @return CellStyle
     */
    public function newStyle();

    /**
     * @return Color
     */
    public function newColor();

    /**
     * @return Fill
     */
    public function newFill();
    
    /**
     * Get the Style(s) added to this Book.
     * 
     * @return \SplObjectStorage
     */
    public function getStyles();

    /**
     * @return \SplObjectStorage
     */
    public function getFills();
}