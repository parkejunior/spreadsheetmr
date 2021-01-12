# SpreadSheetMR

SpreadSheetMR is a simple multiformat spreadsheet reader.

  - Read XLSX, XLS, CSV or TXT files
  - Combine header with data
  - Ignore row or columns

This library uses [`shuchkin/SimpleXLS`](https://github.com/shuchkin/simplexls) and [`shuchkin/SimpleXLSX`](https://github.com/shuchkin/simplexlsx) for read Excel files.

# Installation

First, you will need to install [Composer](http://getcomposer.org/). Then, run the following command:

```bash
$ composer install parkejunior/spreadsheetmr
```


# Usage

## Basic
Here is a basic example of using the library:
```php
use SpreadSheetMR\SpreadSheetMR;

$path_to_file = "file.csv";
$file_extension = ".csv"; // or only "csv"
$import = new SpreadSheetMR($path_to_file, $file_extension);
$data = $import->getObject();

var_dump($data);
```

The `getObject` method get data formatted using `stdClass`.
Note that the file extension is passed as a separate property from the file path, because the path can be temporary like the superglobal `$_FILES['file']['tmp_name']`.

## Verify header and limits

You can use the `verifyFile()` method by passing an array with some settings. Example:
```php
...

$import->verifyFile(array(
	"first_title" => "name", // check if first title on header is "name"
	"last_title" => "phone", // check if last title on header is "phone"
	"total_columns" => 4  // check if total columns on header is 4
));
$data = $import->getObject();

var_dump($data);
```

## Ignore row and columns

It is also possible to ignore columns or rows using the `ignoreRow()` and `ignoreColumn()` methods by passing the index offset as a parameter. Example:
```php
...

$import->ignoreRow(3); // ignore 4th row
$import->ignoreColumn(0); // ignore first column
$data = $import->getObject();

var_dump($data);
```

## Define which row is the header

You can define which line is the header by passing to the `headerIndex` property the index offset of the line. Note that when the header is defined, the `getObject ()` method returns a `stdClass` combining the header as an association to the data of each line. Example:
```php
...

$import->headerIndex = 0; // define first row as header
$data = $import->getObject();

var_dump($data);
```

# Contribution
If you find any incorrect English grammar or any suggestions on how to improve the library, I appreciate it.
