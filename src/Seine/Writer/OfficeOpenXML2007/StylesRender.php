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
use Seine\Parser\DOM\DOMStylesheet;
use Seine\Parser\DOMStyle\Fill;
use Seine\Parser\DOMStyle\Font;
use Seine\Parser\DOMStyle\StylesheetElement;
use Seine\Writer\OfficeOpenXML2007StreamWriter as MyWriter;

final class StylesRender
{
    const FONT_FAMILY_DEFAULT = 'Arial';
    const FONT_SIZE_DEFAULT = 10;

    /**
     * @var IdResolver
     */
    private $idResolver;

	/**
	 * Only one stylesheet supported so far.
	 *
	 * @param DOMStylesheet $styleSheet
	 * @return string
	 */
    public function render(DOMStylesheet $styleSheet)
    {
        $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . MyWriter::EOL;
        $data .= $this->buildStyleFonts($styleSheet->getFonts());
        $data .= $this->buildFills($styleSheet->getFills());
        $data .= $this->buildBorders();
        $data .= $this->buildCellStyles();
        $data .= $this->buildCellXfs($styleSheet);
        $data .= '</styleSheet>';
        return $data;
    }

    private function buildStyleFonts(\Iterator $fonts)
    {
        $data = '';
		/**
		 * @var Font $font
		 */
        $count = 0;
        foreach($fonts as $font) {
            $data .= '        <font>' . MyWriter::EOL;
            if($font->isBold()) {
                $data .= '            <b/>' . MyWriter::EOL;
            }
			if($font->isItalic()) {
				$data .= '            <i/>' . MyWriter::EOL;
			}
            $data .= '            <sz val="' . ($font->getSize() ? $font->getSize() : self::FONT_SIZE_DEFAULT) . '"/>' . MyWriter::EOL;
            $data .= '            <name val="' . ($font->getFamily() ? $font->getFamily() : self::FONT_FAMILY_DEFAULT) . '"/>' . MyWriter::EOL;
            $data .= '            <family val="2"/>' . MyWriter::EOL; // no clue why this needs to be there

            $color = $font->getColor();
            if ($color && $color->getRgb()) {
                $rgb = $color->getRgb();
                $data .= '            <color rgb="' . $rgb . '"/>' . MyWriter::EOL;
            }

            $data .= '        </font>' . MyWriter::EOL;
            ++$count;
        }
        $data .= '    </fonts>' . MyWriter::EOL;
        $data = '    <fonts count="' . $count . '">' . MyWriter::EOL.$data;

        return $data;
    }

    private function buildFills(\Iterator $fillList)
    {
		$doc = new \DOMDocument();
		$doc->formatOutput = true;

		$fillsNode = $doc->createElement('fills');
		$doc->appendChild($fillsNode);

        $count = 0;
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
            ++$count;
		}
        $fillsNode->setAttribute('count', $count);

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
    </cellStyleXfs>
';
    }

    private function buildCellXfs(DOMStylesheet $styleSheet)
    {
        /**
         * @var CellFormatting $format
         */
		$formatList = $styleSheet->getFormats();
        $count = 0;
        $data = '';
        foreach($formatList as $format) {
            $fillId = $this->resolveStyleElementId($format->getFill());
			$fontId = $this->resolveStyleElementId($format->getFont());

			//numFmtId="0"
            $data .= '        <xf fontId="'. $fontId .'" fillId="'. $fillId .'" borderId="0" xfId="0" />' . MyWriter::EOL;
            $count++;
        }
        $data .= '    </cellXfs>' . MyWriter::EOL;
        $data = '    <cellXfs count="' . $count . '">' . MyWriter::EOL . $data;

        return $data;
    }

    private function resolveStyleElementId($el)
    {
        $id = 0;
        if ($el instanceof StylesheetElement) {
            $id = $this->idResolver->resolve($el);
        }

        return $id;
    }

    /**
     * @return IdResolver
     */
    public function getIdResolver()
    {
        return $this->idResolver;
    }

    /**
     * @param IdResolver $idResolver
     */
    public function setIdResolver(IdResolver $idResolver)
    {
        $this->idResolver = $idResolver;
    }
}