<?php
/**
 * @author: yevgen
 */
require_once __DIR__.'/vendor/autoload.php';


$fp = fopen('example.xlsx', 'w');
$configuration = new \Seine\Configuration();
$configuration->setOption(\Seine\Configuration::OPT_WRITER, 'OfficeOpenXML2007StreamWriter');
$factory = new \Seine\Parser\DOM\DOMFactory();
$book = $factory->getConfiguredBook($fp, $configuration);
$writer = $factory->getConfiguredWriter($book, $fp, $configuration);
$sheet = $book->newSheet();



$styleSheet = $book->getDefaultStyleSheet();
$bgColor = $styleSheet->newColor()->setRgb('000000');
$fgColor = $styleSheet->newColor()->setRgb('FFFFFF');
$fill = $styleSheet->newPatternFill();
$fill->setBgColor($bgColor);
$fill->setFgColor($fgColor);

$fontColor = $styleSheet->newColor()->setRgb('ACACAC');
$font = $styleSheet->newFont()->setColor($fontColor);

$format = $styleSheet->newFormatting();
$format->setFill($fill);
$format->setFont($font);

$row = $factory->getRow(['a', 'b', 'c']);
$row->setStyle($format);
$sheet->addRow($row);

$book->close();

fclose($fp);