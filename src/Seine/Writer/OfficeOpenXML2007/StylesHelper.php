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
use Seine\Parser\CellStyle;
use Seine\Parser\DOMStyle\Fill;
use Seine\Writer\OfficeOpenXML2007StreamWriter as MyWriter;

final class StylesHelper
{
    const FONT_FAMILY_DEFAULT = 'Arial';
    const FONT_SIZE_DEFAULT = 10;

    public function render(Book $book)
    {
        $styles = $book->getStyles();
        $fills = $book->getFills();

        $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . MyWriter::EOL;
        $data .= $this->buildStyleFonts($styles);
        $data .= $this->buildFills($fills);
        $data .= $this->buildBorders();
//        $data .= $this->buildCellStyles();
        $data .= $this->buildCellXfs($book, $styles);
        $data .= '
        <cellStyleXfs count="1">
          <xf numFmtId="164" fontId="0" fillId="0" borderId="0" applyFont="true" applyBorder="true" applyAlignment="true" applyProtection="true">
             <alignment horizontal="general" vertical="bottom" textRotation="0" wrapText="false" indent="0" shrinkToFit="false" />
             <protection locked="true" hidden="false" />
          </xf>
       </cellStyleXfs>
       <cellStyles count="1">
          <cellStyle name="Normal" xfId="0" builtinId="0" customBuiltin="false" />
       </cellStyles>
        <colors>
      <indexedColors>
         <rgbColor rgb="FF000000" />
         <rgbColor rgb="FFFFFFFF" />
         <rgbColor rgb="FFFF0000" />
         <rgbColor rgb="FF00FF00" />
         <rgbColor rgb="FF0000FF" />
         <rgbColor rgb="FFFFFF00" />
         <rgbColor rgb="FFFF00FF" />
         <rgbColor rgb="FF00FFFF" />
         <rgbColor rgb="FF800000" />
         <rgbColor rgb="FF008000" />
         <rgbColor rgb="FF000080" />
         <rgbColor rgb="FF808000" />
         <rgbColor rgb="FF800080" />
         <rgbColor rgb="FF008080" />
         <rgbColor rgb="FFC0C0C0" />
         <rgbColor rgb="FF808080" />
         <rgbColor rgb="FF9999FF" />
         <rgbColor rgb="FF993366" />
         <rgbColor rgb="FFFFFFCC" />
         <rgbColor rgb="FFCCFFFF" />
         <rgbColor rgb="FF660066" />
         <rgbColor rgb="FFFF6666" />
         <rgbColor rgb="FF0066CC" />
         <rgbColor rgb="FFCCCCFF" />
         <rgbColor rgb="FF000080" />
         <rgbColor rgb="FFFF00FF" />
         <rgbColor rgb="FFFFFF00" />
         <rgbColor rgb="FF00FFFF" />
         <rgbColor rgb="FF800080" />
         <rgbColor rgb="FF800000" />
         <rgbColor rgb="FF008080" />
         <rgbColor rgb="FF0000FF" />
         <rgbColor rgb="FF00CCFF" />
         <rgbColor rgb="FFCCFFFF" />
         <rgbColor rgb="FFCCFFCC" />
         <rgbColor rgb="FFFFFF99" />
         <rgbColor rgb="FF99CCFF" />
         <rgbColor rgb="FFFF99CC" />
         <rgbColor rgb="FFCC99FF" />
         <rgbColor rgb="FFFFCC99" />
         <rgbColor rgb="FF3366FF" />
         <rgbColor rgb="FF33CCCC" />
         <rgbColor rgb="FF99CC00" />
         <rgbColor rgb="FFFFCC00" />
         <rgbColor rgb="FFFF9900" />
         <rgbColor rgb="FFFF6600" />
         <rgbColor rgb="FF666699" />
         <rgbColor rgb="FF969696" />
         <rgbColor rgb="FF003366" />
         <rgbColor rgb="FF339966" />
         <rgbColor rgb="FF003300" />
         <rgbColor rgb="FF333300" />
         <rgbColor rgb="FF993300" />
         <rgbColor rgb="FF993366" />
         <rgbColor rgb="FF333399" />
         <rgbColor rgb="FF333333" />
      </indexedColors>
   </colors>
        ';
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

        /**
         * @var Fill $fill
         */
        $xml = '<fills count="'. $count .'">';
        foreach ($fillList as $fill) {
            $xml .= '<fill>
            <patternFill patternType="'. $fill->getPatternType() .'"/>';
            if ($color = $fill->getBgColor()) {
                $xml .= '<bgColor rgb="'. $color->getRgb() .'"/>';
            }

            if ($color = $fill->getFgColor()) {
                $xml .= '<fgColor rgb="'. $color->getRgb() .'"/>';
            }

            $xml .= '</fill>';
        }

        return $xml.'</fills>';
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
        return '<cellStyles count="1">
        <cellStyle name="Normal" xfId="0" builtinId="0"/>
    </cellStyles>';
    }

    private function buildCellXfs(Book $book, \SplObjectStorage $styles)
    {
        /**
         * @var CellStyle $style
         */
        $i = 0;
        $data = '    <cellXfs count="' . count($styles) . '">' . MyWriter::EOL;
        foreach($styles as $style) {

            //TODO: delegate to render
            $fillId = $this->getFillIdForStyle($book, $style);

            $data .= '        <xf numFmtId="0" fontId="' . $i . '" fillId="'. $fillId .'" borderId="0" xfId="0" applyFont="' . ($i > 0 ? 1 : 0) . '"/>' . MyWriter::EOL;
            $i++;
        }
        $data .= '    </cellXfs>' . MyWriter::EOL;

        return $data;
    }

    private function getFillIdForStyle(Book $book, CellStyle $style)
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