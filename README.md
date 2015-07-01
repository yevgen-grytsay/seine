Seine
=====

This is the fork of the https://github.com/martinvium/seine repository.
It aims to add new cell formatting features.


Limitations
===========

Package is under construction and thus have some limitations:

* Can not apply formatting to a specific cell or to a group of cells.


Example
=======

This example demonstrates how to apply fill to a row.

```php
<?php
$fp = fopen('example.xlsx', 'w');
$configuration = new \Seine\Configuration();
$configuration->setOption(\Seine\Configuration::OPT_WRITER, 'OfficeOpenXML2007StreamWriter');
$factory = new \Seine\Parser\DOM\DOMFactory();
$writer = $factory->getConfiguredWriter($fp, $configuration);
$book = $factory->getConfiguredBook($fp, $configuration);
$sheet = $book->newSheet();

$fill = $styleSheet->newPatternFill();
$fill->setBgColor(new \Seine\Parser\DOMStyle\Color('FFFFFFFF'));
$fill->setFgColor(new \Seine\Parser\DOMStyle\Color('FF000000'));
$format = $styleSheet->newFormatting();
$format->setFill($fill);

$row = $factory->getRow(['a', 'b', 'c']);
$row->setStyle($format);
$sheet->addRow($row);
?>
```