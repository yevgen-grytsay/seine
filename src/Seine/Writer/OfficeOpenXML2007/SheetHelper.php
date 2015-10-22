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
namespace Seine\Writer\OfficeOpenXML2007;

use Seine\IOException;
use Seine\Sheet;
use Seine\Writer\OfficeOpenXML2007StreamWriter as MyWriter;
use YevgenGrytsay\Ooxml\DOM\CtCol;
use YevgenGrytsay\Ooxml\Sheet\Cell;
use YevgenGrytsay\Ooxml\StyleLookup;

final class SheetHelper
{
    /**
     * @var Sheet
     */
    private $sheet;

    /**
     * @var SharedStringsHelper
     */
    private $sharedStrings;

    private $filename;
    private $stream;
    private $rowId = 0;
    /**
     * @var StyleLookup
     */
    private $styleLookup;

    public function __construct(Sheet $sheet, SharedStringsHelper $sharedStrings, $filename, StyleLookup $styleLookup)
    {
        $this->sheet = $sheet;
        $this->sharedStrings = $sharedStrings;
        $this->filename = $filename;
        $this->styleLookup = $styleLookup;
    }

    /**
     * @param CtCol[] $cols
     *
     * @throws \Seine\IOException
     */
    public function start(array $cols)
    {
        $this->stream = fopen($this->filename, 'w');
        if(! $this->stream) {
            throw new IOException('failed to open stream: ' . $this->filename);
        }

        fwrite($this->stream, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' . MyWriter::EOL);

        $doc = new \DOMDocument();
        $doc->formatOutput = true;
        $colsEl = $doc->createElement('cols');
        $doc->appendChild($colsEl);
        foreach ($cols as $col) {
            $colsEl->appendChild($col->render($doc));
        }
        $colsXml = $doc->saveXML($colsEl);
        fwrite($this->stream, $colsXml);

        fwrite($this->stream, MyWriter::EOL.'    <sheetData>' . MyWriter::EOL);
    }



    public function writeRow($row, array $config = array())
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
//        $writer->openUri('php://output');

        $writer->startDocument('1.0', 'UTF-8');

        $columnId = 'A';
        $rowId = ++$this->rowId;
        $writer->startElement('row');
        $writer->writeAttribute('r', $rowId);

        foreach ($config as $attr => $val) {
            $writer->writeAttribute($attr, $val);
        }

        /**
         * @var  $colIndex
         * @var Cell $cell
         */
        foreach($row as $colIndex => $cell) {
            $value = $cell->getValue();
            $cellStyleId = $this->getStyleId($cell->getStyleReference());

            $writer->startElement('c');
            $writer->writeAttribute('r', $columnId . $rowId);
            $writer->writeAttribute('s', $cellStyleId);

            $cellValue = $value;
            if (!is_numeric($value) && !empty($value)) {
                $sharedStringId = $this->sharedStrings->writeString($value);
                $writer->writeAttribute('t', Cell::TYPE_SHARED_STRING);
                $cellValue = $sharedStringId;
            }

            $writer->writeElement('v', $cellValue);
            $writer->endElement();
            $columnId++;
        }
        $writer->endElement();

        $writer->endDocument();
        $xml = $writer->outputMemory(true);
        $pos = strpos($xml, "\n");
        $xml = substr($xml, $pos + 1);

        fwrite($this->stream, $xml);
    }

    protected function getStyleId($styleReference)
    {
        $styleId = 0;
        if ($this->styleLookup->hasStyle($styleReference)) {
            $styleId = $this->styleLookup->getStyleIdByReference($styleReference);
        }

        return $styleId;
    }

    public function end()
    {
        fwrite($this->stream, '    </sheetData>' . MyWriter::EOL);
        fwrite($this->stream, '</worksheet>');
        fclose($this->stream);
    }
}