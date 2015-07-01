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
use Seine\Compressor;
use Seine\Configuration;
use Seine\Row;
use Seine\Sheet;
use Seine\Writer\OfficeOpenXML2007\SharedStringsHelper;
use Seine\Writer\OfficeOpenXML2007\SheetHelper;
use Seine\Writer\OfficeOpenXML2007\StylesRender;
use Seine\Writer\OfficeOpenXML2007\WriterBase;

/**
 * @link http://www.ecma-international.org/publications/standards/Ecma-376.htm
 * @link http://en.wikipedia.org/wiki/Office_Open_XML
 */
final class OfficeOpenXML2007StreamWriter extends WriterBase
{
    const OPT_TEMP_DIR = Configuration::OPT_TEMP_DIR;

    private $autoCloseStream = false;

    /**
     * @var SharedStringsHelper
     */
    private $sharedStrings;

    /**
     * @var SheetHelper[]
     */
    private $sheetHelpers = array();

    /**
     * @var Book
     */
    private $book;

    /**
     * @param \Seine\Book $book
     * @param resource $stream
     * @param \Seine\Compressor $compressor
     */
    public function __construct(Book $book, $stream, Compressor $compressor)
    {
        $this->book = $book;
        parent::__construct($stream, $compressor);
    }

    public function setConfig(Configuration $config)
    {
        $this->setTempDir($config->getOption(self::OPT_TEMP_DIR, $this->tempDir));
    }

    public function startBook(Book $book)
    {
        $this->createBaseStructure();
        $this->startSharedStrings();
    }

    private function startSharedStrings()
    {
        $filename = $this->dataDir . DIRECTORY_SEPARATOR . 'sharedStrings.xml';
        $this->createEmptyWorkingFile($filename);
        $this->sharedStrings = new SharedStringsHelper($filename);
        $this->sharedStrings->start();
    }
    
    public function startSheet(Sheet $sheet)
    {
        $filename = $this->sheetDir . DIRECTORY_SEPARATOR . 'sheet' . $sheet->getId() . '.xml';
        $this->createEmptyWorkingFile($filename);
        $sheetHelper = new SheetHelper($sheet, $this->sharedStrings, $filename);
        $sheetHelper->start();
        $this->sheetHelpers[$sheet->getId()] = $sheetHelper;
    }
    
    public function writeRow(Sheet $sheet, Row $row)
    {
        $this->sheetHelpers[$sheet->getId()]->writeRow($this->book, $sheet, $row);
    }
    
    public function endSheet(Sheet $sheet)
    {
        $this->sheetHelpers[$sheet->getId()]->end();
    }
    
    public function endBook()
    {
        $book = $this->book;
        $this->sharedStrings->end();
        $this->createBookFile($book->getSheets());
        $this->createStylesFile($book);
        $this->createDataRelationsFile($book->getSheets());
        $this->createContentTypesFile($book->getSheets());
        $this->zipWorkingFiles();
        $this->copyZipToStream();
        $this->cleanWorkingFiles();
    }

    public function setAutoCloseStream($flag) 
    {
        $this->autoCloseStream = (bool)$flag;
    }

    private function createDataRelationsFile(array $sheets)
    {
        $data = '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
    <Relationship Id="rIdSharedStrings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>' . self::EOL;
        
        /* @var Sheet $sheet */
        foreach($sheets as $sheet) {
            $data .= '    <Relationship Id="rId' . $sheet->getId() . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $sheet->getId() . '.xml"/>' . self::EOL;
        }
        
        $data .= '</Relationships>';
        
        $filename = $this->dataRelsDir . DIRECTORY_SEPARATOR . 'workbook.xml.rels';
        $this->createWorkingFile($filename, $data);
    }
    
    private function createContentTypesFile(array $sheets)
    {
        $data = '<?xml version="1.0" encoding="UTF-8"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Override PartName="/_rels/.rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Override PartName="/xl/_rels/workbook.xml.rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' . self::EOL;
    
        /* @var Sheet $sheet */
        foreach($sheets as $sheet) {
            $data .= '    <Override PartName="/xl/worksheets/sheet' . $sheet->getId() . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' . self::EOL;
        }
        
        $data .= '    <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
    <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
    <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
    <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
    <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
</Types>';

        $contentTypesFile = $this->baseDir . DIRECTORY_SEPARATOR . '[Content_Types].xml';
        $this->createWorkingFile($contentTypesFile, $data);
    }
    
    private function createBookFile(array $sheets)
    {
        $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <sheets>' . self::EOL;
        
        /* @var Sheet $sheet */
        foreach($sheets as $sheet) {
            $data .= '        <sheet name="' . $this->escape($sheet->getName()) . '" sheetId="' . $sheet->getId() . '" r:id="rId' . $sheet->getId() . '"/>' . self::EOL;
        }
        
        $data .= '    </sheets>
</workbook>';
        
        $filename = $this->dataDir . DIRECTORY_SEPARATOR . 'workbook.xml';
        $this->createWorkingFile($filename, $data);
    }

    /**
     * @param \Seine\Book $book
     *
     * @throws \Seine\IOException
     * @internal param \Seine\Style[]|\SplObjectStorage $styles
     *
     */
    private function createStylesFile(Book $book)
    {
        $stylesHelper = new StylesRender();
        $filename = $this->dataDir . DIRECTORY_SEPARATOR . 'styles.xml';
        $this->createWorkingFile($filename, $stylesHelper->render($book->getStyleSheetList()[0]));
    }
}