<?php
header("Content-Type: application/javascript; charset=utf-8");
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
require_once("../../new_head/safety_news_db_0713.php");

$memberId = $_POST['memberId'];
$searchDate = $_POST['searchDate'];
$page = $_POST['page'];
$onePage = $_POST['onePage'];

$searchDate = str_replace('"','',$searchDate);

//날짜 범위 및 한번에 가져올 포스트 범위 계산
$searchEndDate = date("Y-m-d", strtotime($searchDate . "+1 day"));
$currentLimit = ($onePage * $page) - $onePage;
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage;

//쿼리 조건과 쿼리문
$searchSql = ' where WO.id="' . $memberId . '" and WO.serialNumber=WL.serialNumber and WL.detectedTime >= "' . $searchDate . '" and WL.detectedTime < "' . $searchEndDate . '"';
$sql = "select * from Works_On WO, Works_List WL" .$searchSql . $sqlLimit;
$result_Works_On = mysqli_query($db,$sql);

//결과 저장
$result = array();
while($row_Works_On = mysqli_fetch_array($result_Works_On)){
	array_push($result,array('serialNumber'=>$row_Works_On[0], 'id'=>$row_Works_On[1], 'conduct'=>$row_Works_On[2], 'workLocation'=>$row_Works_On[3], 'detectedTime'=>$row_Works_On[5], 'finishedTime'=>$row_Works_On[6], 'progressState'=>$row_Works_On[7], 'newflag'=>$row_Works_On[8]));
	//$allPost++;
}

array_unshift($result,array('allPost'=>sizeof($result)));
echo json_encode($result);
?>