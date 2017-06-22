<?php
namespace script;

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit','2G');
date_default_timezone_set('Europe/London');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

require_once dirname(__FILE__) . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

//$file = file_get_contents(dirname(__FILE__).'/storeexcel.xlsx');
$PHPExcel = \PHPExcel_IOFactory::load(dirname(__FILE__).'/storeexcel.xlsx');
$sheet = $PHPExcel->getActiveSheet(); // 读取第一個工作表

$highestRow = $sheet->getHighestRow();
$highestColumnIndex = $sheet->getHighestColumn();
$highestColumnIndex = 15;
//var_dump($highestColumnIndex);exit;
$excelData = array();
for ($row = 1; $row <= $highestRow; $row++) {
    for ($col = 0; $col < $highestColumnIndex; $col++) {
        $excelData[$row][] =(string)$sheet->getCellByColumnAndRow($col, $row)->getValue();
    }
}
var_dump($excelData[1]);
exit;

?>
