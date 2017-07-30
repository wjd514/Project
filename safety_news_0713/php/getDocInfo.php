<?php
header("Content-Type: application/javascript; charset=utf-8");
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
require_once("../../new_head/safety_news_db_0713.php");

mysqli_query($db,"set session character_set_connection=utf8;");
mysqli_query($db,"set session character_set_results=utf8;");
mysqli_query($db,"set session character_set_client=utf8;");	
mysqli_set_charset($db,"utf8");
	
$bNo = $_GET['bNo'];
$memberId = $_GET['memberId'];

$result = array();

$sql_MemberInfo = 'select phoneNumber, name, company, position, section, authority from MemberInfo where id="' . $memberId . '"';
$result_MemberInfo = mysqli_query($db,$sql_MemberInfo);
$row_MemberInfo = $result_MemberInfo->fetch_assoc();

if($row_MemberInfo){
	array_push($result, array('phoneNumber'=>$row_MemberInfo['phoneNumber'],'name'=>$row_MemberInfo['name'],'company'=>$row_MemberInfo['company'],'position'=>$row_MemberInfo['position'],'section'=>$row_MemberInfo['section'],'authority'=>$row_MemberInfo['authority']));
}


$sql_HeaderInfo = 'select * from Works_On WO, Works_List WL where WO.id="' . $memberId . '" and WO.serialNumber="' . $bNo . '" and WO.serialNumber = WL.serialNumber';
$result_HeaderInfo = mysqli_query($db,$sql_HeaderInfo);
//$row_HeaderInfo = $result_HeaderInfo->fetch_assoc();

while($row_HeaderInfo = mysqli_fetch_array($result_HeaderInfo)){
//if($row_HeaderInfo){ //왜안되는지 모르겟네 그래서 하나밖에 없지만 while문으로..
	array_push($result, array('conduct'=>$row_HeaderInfo[2],'workLocation'=>$row_HeaderInfo[3],'detectedTime'=>$row_HeaderInfo[5],'finisedTime'=>$row_HeaderInfo[6],'progressState'=>$row_HeaderInfo[7]));
}

if($result[1]['progressState'] == 0 && strcmp($result[1]['conduct'],"executor") === 0){
	$sql_update = "update Works_List set progressState= 1 where serialNumber='" .$bNo. "'";
	$result_update = mysqli_query($db,$sql_update);
}
if($result[1]['progressState'] < 3){
	$sql_DocInfo = 'select * from Progress where serialNumber="' . $bNo .'"';
	$result_DocInfo = mysqli_query($db,$sql_DocInfo);
	while($row_DocInfo = mysqli_fetch_array($result_DocInfo)){
		array_push($result, array('discoveredNumbers'=>$row_DocInfo[1],'discoveredMatters'=>$row_DocInfo[2],'indicationNumbers'=>$row_DocInfo[3],'requestContents'=>$row_DocInfo[4],'requestCount'=>$row_DocInfo[5],'performContents'=>$row_DocInfo[6],'performCount'=>$row_DocInfo[7]));
	}
}
else{
	$sql_DocInfo = 'select * from DocBodyInfo where serialNumber="' . $bNo .'"';
	$result_DocInfo = mysqli_query($db,$sql_DocInfo);
	$row_DocInfo = $result_DocInfo->fetch_assoc();
	
	$boundary = "*****";
	$indicationNum = 1;
	//echo "fdsff";
	if($row_DocInfo){
		$split_indications = explode($boundary,$row_DocInfo[indications]);
		for($i=0; $i<count($split_indications); $i+=2){
			array_push($result,array('discoveredNumbers'=>$row_DocInfo['discoveredNumbers'], 'discoveredMatters'=> $row_DocInfo['discoveredMatters'], 'indicationNumbers'=>$indicationNum, 'requestContents'=>$split_indications[$i], 'performContents'=>$split_indications[$i+1], 'additionalDetails'=>$row_DocInfo['additionalDetails']));
			$indicationNum++;
		}
	}
}

echo json_encode($result);


?>