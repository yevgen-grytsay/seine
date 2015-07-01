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
namespace Seine\Writer;

use Seine\Book;
use Seine\WriterFactory;
use Seine\ZipArchiveCompressor;

class WriterFactoryImpl implements WriterFactory
{
    /**
     * @param \Seine\Book $book
     * @param resource $stream
     *
     * @return \Seine\Writer\OfficeOpenXML2007StreamWriter
     */
    public function getOfficeOpenXML2007StreamWriter(Book $book, $stream)
    {
        $compressor = new ZipArchiveCompressor;
        return new OfficeOpenXML2007StreamWriter($book, $stream, $compressor);
    }

    /**
     * @param \Seine\Book $book
     * @param resource $stream
     *
     * @return \Seine\Writer\OfficeXml2003StreamWriter
     */
    public function getOfficeXML2003StreamWriter(Book $book, $stream)
    {
        return new OfficeXML2003StreamWriter($book, $stream);
    }

    /**
     * @param \Seine\Book $book
     * @param resource $stream
     *
     * @return \Seine\Writer\CsvStreamWriter
     */
    public function getCsvStreamWriter(Book $book, $stream)
    {
        return new CsvStreamWriter($stream);
    }
}