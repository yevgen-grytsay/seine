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
use Seine\Parser\DOMStyle\NumberFormat;
use Seine\Writer\OfficeOpenXML2007StreamWriter as MyWriter;
use YevgenGrytsay\Ooxml\DOM\CtBorder;

final class StylesRender
{
    const FONT_FAMILY_DEFAULT = 'Arial';
    const FONT_SIZE_DEFAULT = 10;

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
        $data .= $this->buildNumberFormats($styleSheet->getNumberFormats());
        $data .= $this->buildStyleFonts($styleSheet->getFonts());
        $data .= $this->buildFills($styleSheet->getFills());
        $data .= $this->buildBorders($styleSheet->getBorders());
        $data .= $this->buildCellStyles();
        $data .= $this->buildCellXfs($styleSheet);
        $data .= '</styleSheet>';
        return $data;
    }

    /**
     * @param \SplObjectStorage $formats
     *
     * @return string
     */
    private function buildNumberFormats(\SplObjectStorage $formats)
    {
        $doc = new \DOMDocument();
        $doc->formatOutput = true;

        $container = $doc->createElement('numFmts');
        $container->setAttribute('count', $formats->count());
        $doc->appendChild($container);

        $id = 164;
        /** @var NumberFormat $format */
        foreach ($formats as $format) {
            $formats->offsetSet($format, $id);
            $fmt = $doc->createElement('numFmt');
            $container->appendChild($fmt);
            $fmt->setAttribute('numFmtId', $id);
            $fmt->setAttribute('formatCode', $format->getFormatCode());
            ++$id;
        }

        return $doc->saveXML($container);
//        return '
//            <numFmts count="5">
//                <numFmt numFmtId="164" formatCode="&quot;$&quot;#,##0.00"/>
//                <numFmt numFmtId="165"
//                formatCode="&quot;Yes&quot;;&quot;Yes&quot;;&quot;No&quot;"/>
//                <numFmt numFmtId="166"
//                formatCode="&quot;True&quot;;&quot;True&quot;;&quot;False&quot;"/>
//                <numFmt numFmtId="167"
//                formatCode="&quot;On&quot;;&quot;On&quot;;&quot;Off&quot;"/>
//                <numFmt numFmtId="168"
//                formatCode="[$€-2]\ #,##0.00_);[Red]\([$€-2]\ #,##0.00\)"/>
//            </numFmts>
//        ';
    }

    private function buildStyleFonts(\SplObjectStorage $fonts)
    {
        $data = '    <fonts count="' . count($fonts) . '">' . MyWriter::EOL;
		/**
		 * @var Font $font
		 */
        foreach($fonts as $font) {
            $data .= '        <font>' . MyWriter::EOL;
            if($font->isBold()) {
                $data .= '            <b/>' . MyWriter::EOL;
            }
			if($font->isItalic()) {
				$data .= '            <i/>' . MyWriter::EOL;
			}
            $data .= '            <sz val="' . ($font->getSize() ?: self::FONT_SIZE_DEFAULT) . '"/>' . MyWriter::EOL;
            $data .= '            <name val="' . ($font->getFamily() ?: self::FONT_FAMILY_DEFAULT) . '"/>' . MyWriter::EOL;
            $data .= '            <family val="2"/>' . MyWriter::EOL; // no clue why this needs to be there

            $color = $font->getColor();
            if ($color && $color->getRgb()) {
                $rgb = $color->getRgb();
                $data .= '            <color rgb="' . $rgb . '"/>' . MyWriter::EOL;
            }

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

    private function buildBorders(\SplObjectStorage $borderList)
    {
        $doc = new \DOMDocument();
        $doc->formatOutput = true;

        $rootEl = $doc->createElement('borders');
        $rootEl->setAttribute('count', $borderList->count());

        /** @var CtBorder $border */
        foreach ($borderList as $border) {
            $rootEl->appendChild($border->render($doc));
        }
        $xml = $doc->saveXML($rootEl);

        return $xml;
//        return '    <borders count="1">
//        <border>
//            <left/>
//            <right/>
//            <top/>
//            <bottom/>
//            <diagonal/>
//        </border>
//    </borders>';
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
        $doc = new \DOMDocument();
        $doc->formatOutput = true;

        /**
         * @var CellFormatting $format
         */
		$formatList = $styleSheet->getStyles();
        $i = 0;
        $cellXfRootEl = $doc->createElement('cellXfs');
        $doc->appendChild($cellXfRootEl);
        $cellXfRootEl->setAttribute('count', count($formatList));
        foreach($formatList as $format) {
            $fillId = $this->getFillIdForStyle($styleSheet, $format);
			$fontId = $this->getFontIdForStyle($styleSheet, $format);
            $numberFormatId = $this->getNumberFormatIdForStyle($styleSheet, $format);
            $borderId = $this->getBorderIdForStyle($styleSheet, $format);
            $align = $format->getAlign();

            $xfEl = $doc->createElement('xf');
            $cellXfRootEl->appendChild($xfEl);

            if ($numberFormatId) {
                $xfEl->setAttribute('numFmtId', $numberFormatId);
                $xfEl->setAttribute('applyNumberFormat', '1');
            }

            $xfEl->setAttribute('fontId', $fontId);
            $xfEl->setAttribute('fillId', $fillId);
            $xfEl->setAttribute('borderId', $borderId);
            $xfEl->setAttribute('xfId', 0);

            if ($align) {
                $alignEl = $align->render($doc);
                $xfEl->appendChild($alignEl);
            }

            $i++;
        }
        $xml = $doc->saveXML($cellXfRootEl);

        return $xml;
    }

    private function getBorderIdForStyle(DOMStylesheet $styleSheet, CellFormatting $style)
    {
        $borderId = 0;
        $border = $style->getBorder();
        $borderStorage = $styleSheet->getBorders();
        if ($border && $borderStorage->contains($border)) {
            $borderId = $borderStorage->offsetGet($border);
        }

        return $borderId;
    }

    private function getFillIdForStyle(DOMStylesheet $styleSheet, CellFormatting $style)
    {
        $fillId = 0;
        $fill = $style->getFill();
        $fillStorage = $styleSheet->getFills();
        if ($fill && $fillStorage->contains($fill)) {
            $fillId = $fillStorage->offsetGet($fill);
        }

        return $fillId;
    }

	private function getFontIdForStyle(DOMStylesheet $styleSheet, CellFormatting $style)
	{
		$fontId = 0;
		$font = $style->getFont();
		$fontStorage = $styleSheet->getFonts();
		if ($font && $fontStorage->contains($font)) {
			$fontId = $fontStorage->offsetGet($font);
		}

		return $fontId;
	}

    private function getNumberFormatIdForStyle(DOMStylesheet $styleSheet, CellFormatting $style)
    {
        $formatId = 0;
        $format = $style->getNumberFormat();
        $formatStorage = $styleSheet->getNumberFormats();
        if ($format && $formatStorage->contains($format)) {
            $formatId = $formatStorage->offsetGet($format);
        }

        return $formatId;
    }
}