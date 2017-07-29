<?php
header("Content-Type: application/javascript; charset=utf-8");
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
require_once("../../new_head/safety_news_db_0713.php");


/*
$value = $_POST['jsonInfo'];
$request = file_POST_contents('php://input');
$info = json_decode(stripcslashes($request),true);
$memberName = $info['memberName'];
$searchDate = $info['searchDate'];
$searchColumn = $info['searchColumn'];
$searchText = $info['searchText'];
$page = $info['page'];
$onePage = $info['onePage'];

$memberName = $_POST['memberName'];
$searchDate = $_POST['searchDate'];
$page = $_POST['page'];
$onePage = $_POST['onePage'];
*/
//$e = array('memberName'=> $memberName, 'searchDate'=>$searchDate,'page'=>$page);
//echo json_encode($e);

$memberName = $_POST['memberName'];
$searchDate = $_POST['searchDate'];
$searchColumn = $_POST['searchColumn'];
$searchText = $_POST['searchText'];
$page = $_POST['page'];
$onePage = $_POST['onePage'];

$searchEndDate = date("Y-m-d", strtotime($searchDate."+1 day"));

if(!searchColumn || !searchText){
	$searchSql = ' where conduct="' . $memberName . '" and detectedTime="' . $searchDate . '" and detectedTime < "' . $searchEndDate . '" and ' . $searchColumn . ' like "%' . $searchText . '%"';
}else{
	$searchSql = ' where conduct="' . $memberName . '" and detectedTime >= "' . $searchDate . '" and detectedTime < "' . $searchEndDate . '"';
}

//$sql_DocHeader = 'select count(*) as cnt from DocHeaderInfo' . $searchSql;
$sql_WorkList = 'select count(*) as cnt from WorkList' . $searchSql;

//$result_DocHeader = mysqli_query($db,$sql_DocHeader);
//$row_DocHeader = $result_DocHeader->fetch_assoc();
//$allPost = $row_DocHeader['cnt'];

$result_WorkList = mysqli_query($db,$sql_WorkList);
$row_WorkList = $result_WorkList->fetch_assoc();
$allPost = $row_WorkList['cnt'];

$currentLimit = ($onePage * $page) - $onePage;
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage;
//$sql_DocHeader = 'select serialNumber, conduct, detectedTime from DocHeaderInfo' . $searchSql . $sqlLimit;
$sql_WorkList = 'select serialNumber, conduct, detectedTime, progressState from WorkList' . $searchSql . $sqlLimit;

//$result_DocHeader = mysqli_query($db,$sql_DocHeader);
$result_WorkList = mysqli_query($db,$sql_WorkList);

$result = array();
array_push($result,array('allPost'=>$allPost));
/*
while($row_DocHeader = mysqli_fetch_array($result_DocHeader)){
	array_push($result,array('serialNumber'=>$row_DocHeader[0],'conduct'=>$row_DocHeader[1],'detectedTime'=>$row_DocHeader[2],'progressState'=>2));
}
*/
while($row_WorkList = mysqli_fetch_array($result_WorkList)){
	array_push($result,array('serialNumber'=>$row_WorkList[0],'conduct'=>$row_WorkList[1],'detectedTime'=>$row_WorkList[2],'progressState'=>$row_WorkList[3]));
}

//echo $_REQUEST['callback'] . '(' . json_encode($searchSql) . ')';
echo json_encode($result);
?>