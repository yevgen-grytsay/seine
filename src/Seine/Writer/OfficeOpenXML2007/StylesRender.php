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

use Seine\Book;
use Seine\Parser\CellFormatting;
use Seine\Parser\DOMStyle\Fill;
use Seine\Writer\OfficeOpenXML2007StreamWriter as MyWriter;

final class StylesRender
{
    const FONT_FAMILY_DEFAULT = 'Arial';
    const FONT_SIZE_DEFAULT = 10;

    public function render(Book $book)
    {
        $styles = $book->getFormats();
        $fills = $book->getFills();

        $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . MyWriter::EOL;
        $data .= $this->buildStyleFonts($styles);
        $data .= $this->buildFills($fills);
        $data .= $this->buildBorders();
        $data .= $this->buildCellStyles();
        $data .= $this->buildCellXfs($book, $styles);
        $data .= '</styleSheet>';
        return $data;
    }

    private function buildStyleFonts(\SplObjectStorage $styles)
    {
        $data = '    <fonts count="' . count($styles) . '">' . MyWriter::EOL;
        foreach($styles as $style) {
            $data .= '        <font>' . MyWriter::EOL;
            if($style->getFontBold()) {
                $data .= '            <b/>' . MyWriter::EOL;
            }
            $data .= '            <sz val="' . ($style->getFontSize() ? $style->getFontSize() : self::FONT_SIZE_DEFAULT) . '"/>' . MyWriter::EOL;
            $data .= '            <name val="' . ($style->getFontFamily() ? $style->getFontFamily() : self::FONT_FAMILY_DEFAULT) . '"/>' . MyWriter::EOL;
            $data .= '            <family val="2"/>' . MyWriter::EOL; // no clue why this needs to be there
            $data .= '        </font>' . MyWriter::EOL;
        }
        $data .= '    </fonts>' . MyWriter::EOL;
        return $data;
    }

    private function buildFills(\SplObjectStorage $fillList)
    {
        $count = $fillList->count();
        //TODO: delegate to render
        //TODO: support indexed colors

		$doc = new \DOMDocument();
		$doc->formatOutput = true;

		$fillsNode = $doc->createElement('fills');
		$doc->appendChild($fillsNode);
		$fillsNode->setAttribute('count', $count);

		foreach ($fillList as $fill) {
			$fillNode = $doc->createElement('fill');

			$patternFill = $doc->createElement('patternFill');
			$patternFill->setAttribute('patternType', $fill->getPatternType());
			$fillNode->appendChild($patternFill);

			if ($color = $fill->getFgColor()) {
				$colorNode = $doc->createElement('fgColor');
				$colorNode->setAttribute('rgb', $color->getRgb());
				$patternFill->appendChild($colorNode);
			}

			if ($color = $fill->getBgColor()) {
				$colorNode = $doc->createElement('bgColor');
				$colorNode->setAttribute('rgb', $color->getRgb());
				$patternFill->appendChild($colorNode);
			}

			$fillsNode->appendChild($fillNode);
		}
		return $doc->saveXML($fillsNode);
        /**
         * @var Fill $fill
         */
        /*$xml = '<fills count="'. $count .'">';
        foreach ($fillList as $fill) {
            $xml .= MyWriter::EOL.'<fill>
            <patternFill patternType="'. $fill->getPatternType() .'">';

			if ($color = $fill->getFgColor()) {
				$xml .= '<fgColor rgb="'. $color->getRgb() .'"/>'.MyWriter::EOL;
			}

            if ($color = $fill->getBgColor()) {
                $xml .= '<bgColor rgb="'. $color->getRgb() .'"/>'.MyWriter::EOL;
            }
            $xml .= '</patternFill></fill>';
        }

        return $xml.'</fills>';*/
    }

    private function buildBorders()
    {
        return '    <borders count="1">
        <border>
            <left/>
            <right/>
            <top/>
            <bottom/>
            <diagonal/>
        </border>
    </borders>';
    }

    private function buildCellStyles()
    {
        return '
		<cellStyleXfs count="1">
			<xf fontId="0" fillId="0" borderId="0" />
		</cellStyleXfs>';
    }

    private function buildCellXfs(Book $book, \SplObjectStorage $formatList)
    {
        /**
         * @var CellFormatting $format
         */
        $i = 0;
        $data = '    <cellXfs count="' . count($formatList) . '">' . MyWriter::EOL;
        foreach($formatList as $format) {

            //TODO: delegate to render
            $fillId = $this->getFillIdForStyle($book, $format);

			//numFmtId="0"
            $data .= '        <xf fontId="0" fillId="'. $fillId .'" borderId="0" xfId="0" />' . MyWriter::EOL;
            $i++;
        }
        $data .= '    </cellXfs>' . MyWriter::EOL;

        return $data;
    }

    private function getFillIdForStyle(Book $book, CellFormatting $style)
    {
        $fillId = 0;
        $fill = $style->getFill();
        $fillStorage = $book->getFills();
        if ($fill && $fillStorage->contains($fill)) {
            $fillId = $fillStorage->offsetGet($fill);
        }

        return $fillId;
    }
}