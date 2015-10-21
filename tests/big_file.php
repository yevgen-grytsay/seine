<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 16.10.15
 */
use Seine\Parser\DOMStyle\PatternFill;

require_once __DIR__.'/../vendor/autoload.php';

function generator($rows, $cols, $style)
{
    $faker = \Faker\Factory::create();
    $array = new \SplFixedArray($rows);
    foreach (range(0, $rows - 1) as $i) {
        $row = array();
        foreach (range(0, $cols - 1) as $j) {
//            $row[$j] = $faker->text(15);
            $value = $faker->randomFloat();
            $cell = new \YevgenGrytsay\Ooxml\Sheet\Cell($value, $style);
            $row[$j] = $cell;
        }
        $array->offsetSet($i, $row);
    }

    return $array;
}

$fp = fopen('example.xlsx', 'w');
$configuration = new \Seine\Configuration();
$configuration->setOption(\Seine\Configuration::OPT_WRITER, 'OfficeOpenXML2007StreamWriter');
$factory = new \Seine\Parser\DOM\DOMFactory();
$book = new \Seine\Parser\DOM\DOMBook($factory);
$book->setWriter(new \Seine\Writer\OfficeOpenXML2007StreamWriter($book, $fp, new \Seine\ZipArchiveCompressor()));


/**
 * Style
 */
$styles = $book->getDefaultStyleSheet();

$defaultStyleConfig = array(
    //'color' => 'F2DEDE',
    'font' => array(
        'size'  => 12,
        'name'  => 'Arial',
        'color' => '000000'
    ),
    'fill' => array(
        'patternType' => PatternFill::PATTERN_SOLID,
        'bgColor'     => '464646',
        'fgColor'     => 'ededed'
    ),
    'numberFormat' => array(
        'formatCode' => '[$₩-412]#,##0.00'
    )
);

$emphasizeStyleConfig = array_merge_recursive($defaultStyleConfig, array(
    'font' => array(
        'color' => 'ff0000'
    ),
    'fill' => array(
        'patternType' => PatternFill::PATTERN_SOLID,
        'bgColor'     => '000000',
        'fgColor'     => '000000'
    )
));

$defaultStyle = $book->defineStyle($defaultStyleConfig);
//$emphasizeStyle = $book->defineStyle($defaultStyleConfig);

//$formatting = $styles->newFormatting();
//$colorFill = $styles->newColor()->setRgb('F2DEDE');
//$colorFont = $styles->newColor()->setRgb('FF0000');
//$font = $styles->newFont()->setColor($colorFont);
//$fill = $styles->newPatternFill()
//    ->setPatternType(PatternFill::PATTERN_SOLID)
//    ->setBgColor($colorFill)
//    ->setFgColor($colorFill);
//$numberFormat = $styles->newNumberFormat('[$₩-412]#,##0.00');
//$formatting->setFont($font)->setFill($fill)->setNumberFormat($numberFormat);
//
//$defaultFormatting = $styles->newFormatting();

$sheet = $book->getDefaultSheet();
foreach (generator(2, 25, $defaultStyle) as $cells) {
//    $style = $defaultStyle;
//    if (mt_rand(1, 9) % 3 === 0) {
//        $style = $emphasizeStyle;
//    }

    $sheet->appendRow($cells);
}

$book->close();

fclose($fp);