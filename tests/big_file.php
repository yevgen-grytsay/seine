<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 16.10.15
 */
use Seine\Parser\DOMStyle\PatternFill;

require_once __DIR__.'/../vendor/autoload.php';

function generator($rows, $cols)
{
    $faker = \Faker\Factory::create();
    foreach (range(1, $rows) as $i) {
        $row = [];
        foreach (range(0, $cols - 1) as $j) {
//            $row[$j] = $faker->text(15);
            $row[$j] = $faker->randomFloat();
        }
        yield $row;
    }
}

$fp = fopen('example.xlsx', 'w');
$configuration = new \Seine\Configuration();
$configuration->setOption(\Seine\Configuration::OPT_WRITER, 'OfficeOpenXML2007StreamWriter');
$factory = new \Seine\Parser\DOM\DOMFactory();
$book = $factory->getConfiguredBook($fp, $configuration);
$sheet = $book->newSheet();

/**
 * Style
 */
$styles = $book->getDefaultStyleSheet();
$formatting = $styles->newFormatting();
$colorFill = $styles->newColor()->setRgb('F2DEDE');
$colorFont = $styles->newColor()->setRgb('FF0000');
$font = $styles->newFont()->setColor($colorFont);
$fill = $styles->newPatternFill()
    ->setPatternType(PatternFill::PATTERN_SOLID)
    ->setBgColor($colorFill)
    ->setFgColor($colorFill);
$numberFormat = $styles->newNumberFormat('[$₩-412]#,##0.00');
$formatting->setFont($font)->setFill($fill)->setNumberFormat($numberFormat);

foreach (generator(6000, 25) as $cells) {
    $row = $factory->getRow($cells);

    if (mt_rand(1, 9) % 3 === 0) {
        $row->setStyle($formatting);
    }
    $sheet->addRow($row);
}

$book->close();

fclose($fp);