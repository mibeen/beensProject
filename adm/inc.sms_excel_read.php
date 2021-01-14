<?php
require G5_PLUGIN_PATH . '/PhpSpreadsheet/vendor/autoload.php';

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($tmp_file);
$sheet = $spreadsheet->getActiveSheet();

$maxRow = $sheet->getHighestRow();          // 마지막 라인

$target = "A"."1".":"."B"."$maxRow";		// A, B열의 내용
$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);


# 라인수 만큼 루프
$cr = "";
foreach ($lines as $key => $line) {
	$hp_no = $line[0];

	if ($hp_no == '') continue;		// 휴대폰번호 없으면 continue

	# 휴대폰 번호 검증
	if (preg_match("/[^0-9-]/", $hp_no)) continue;		// 숫자, 하이픈(-) 이 아닌것 있으면 continue

	$hp_no = preg_replace("/(0(?:2|[0-9]{2}))([0-9]+)([0-9]{4}$)/", "\\1-\\2-\\3", $hp_no);


	$ma_list .= $cr . $hp_no . "||" . $line[1];
	$cr = "\n";
}
?>