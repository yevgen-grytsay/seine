<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 16.10.15
 */
use Seine\Parser\CellFormatting;
use Seine\Parser\DOMStyle\Fill;
use Seine\Parser\DOMStyle\Font;
use Seine\Parser\DOMStyle\NumberFormat;
use Seine\Parser\DOMStyle\PatternFill;
use YevgenGrytsay\Ooxml\DOM\CtBorder;
use YevgenGrytsay\Ooxml\DOM\CtBorderPr;
use YevgenGrytsay\Ooxml\DOM\CtCellAlignment;
use YevgenGrytsay\Ooxml\DOM\CtColor;
use YevgenGrytsay\Ooxml\DOM\StBorderStyle;

require_once __DIR__.'/../vendor/autoload.php';

function generator($rows, $cols, $defaultStyle, $emphasizeStyle)
{
    $faker = \Faker\Factory::create();
    $array = new \SplFixedArray($rows);
    foreach (range(0, $rows - 1) as $i) {
        $row = array();


        $style = $defaultStyle;
        if (mt_rand(1, 9) % 3 === 0) {
            $style = $emphasizeStyle;
        }

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
$defaultStyleConfig = array(
    //'color' => 'F2DEDE',
    'font' => array(
        Font::CONFIG_SIZE  => 12,
        Font::CONFIG_COLOR => '000000',
        Font::CONFIG_BOLD => true
    ),
//    'fill' => array(
//        'patternType' => PatternFill::PATTERN_SOLID,
//        'bgColor'     => '464646',
//        'fgColor'     => 'ededed'
//    ),
    'numberFormat' => array(
        NumberFormat::CONFIG_FORMAT_CODE => '[$₩-412]#,##0.00'
//        'formatCode' => '##0.00'
    )
);

$emphasizeStyleConfig = array(
    CellFormatting::CONFIG_FONT => array(
        Font::CONFIG_SIZE  => 24,
        Font::CONFIG_COLOR => 'ff0000'
    ),
//    CellFormatting::CONFIG_FILL => array(
//        Fill::CONFIG_PATTERN_TYPE => PatternFill::PATTERN_SOLID,
//        Fill::CONFIG_BACK_COLOR     => '000000',
//        Fill::CONFIG_FORE_COLOR     => '000000'
//    ),
    CellFormatting::CONFIG_ALIGNMENT => array(
        CtCellAlignment::ATTR_HORIZONTAL => CtCellAlignment::HOR_CENTER,
        CtCellAlignment::ATTR_VERTICAL => CtCellAlignment::VERT_TOP,
        CtCellAlignment::ATTR_WRAP_TEXT => 'true'
    ),
    CellFormatting::CONFIG_BORDER => array(
        CtBorder::ATTR_OUTLINE        => true,
        CtBorder::ATTR_BORDER_PR_LIST => array(
            CtBorder::BORDER_BOTTOM => array(
                CtBorderPr::COLOR => array(
                    CtColor::ATTR_RGB => '00ff00'
                ),
                CtBorderPr::ATTR_STYLE => StBorderStyle::DOUBLE
            ),
            CtBorder::BORDER_LEFT => array(
                CtBorderPr::COLOR => array(
                    CtColor::ATTR_RGB => 'FFFF99FF'
                ),
                CtBorderPr::ATTR_STYLE => StBorderStyle::DOUBLE
            ),
            CtBorder::BORDER_RIGHT => array(
                CtBorderPr::COLOR => array(
                    CtColor::ATTR_RGB => 'FFFF99FF'
                ),
                CtBorderPr::ATTR_STYLE => StBorderStyle::DOUBLE
            ),
            CtBorder::BORDER_TOP => array(
                CtBorderPr::COLOR => array(
                    CtColor::ATTR_RGB => 'FFFF99FF'
                ),
                CtBorderPr::ATTR_STYLE => StBorderStyle::DOUBLE
            )
        )
    )
);

$defaultStyle = $book->defineStyle($defaultStyleConfig);
$emphasizeStyle = $book->defineStyle($emphasizeStyleConfig);


$sheet = $book->startSheet();
$sheet->setColsConfig(array(
    array(
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_MAX => 1,
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_MIN => 1,
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_WIDTH => 100
    ),
    array(
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_MAX => 2,
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_MIN => 2,
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_WIDTH => 50
    ),
    array(
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_MAX => 3,
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_MIN => 3,
        \YevgenGrytsay\Ooxml\DOM\CtCol::ATTR_WIDTH => 25
    )
));
foreach (generator(200, 25, $defaultStyle, $emphasizeStyle) as $cells) {
//    $style = $defaultStyle;
//    if (mt_rand(1, 9) % 3 === 0) {
//        $style = $emphasizeStyle;
//    }

    $sheet->appendRow($cells);
}

$book->close();

fclose($fp);