<?php
require G5_PLUGIN_PATH . '/PhpSpreadsheet/vendor/autoload.php';

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($file_excel);
$sheet = $spreadsheet->getActiveSheet();

$maxRow = $sheet->getHighestRow();          // 마지막 라인

$target = "A"."1".":"."D"."$maxRow";		// A ~ D열의 내용
$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);
?>