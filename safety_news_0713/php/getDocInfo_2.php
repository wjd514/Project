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


$sql_HeaderInfo = 'select * from WorkList where id="' . $memberId . '" and serialNumber="' . $bNo .'"';
$result_HeaderInfo = mysqli_query($db,$sql_HeaderInfo);
$row_HeaderInfo = $result_HeaderInfo->fetch_assoc();

if($row_HeaderInfo){
	array_push($result, array('conduct'=>$row_HeaderInfo['conduct'],'detectedTime'=>$row_HeaderInfo['detectedTime'],'finisedTime'=>$row_HeaderInfo['finisedTime'],'progressState'=>$row_HeaderInfo['progressState']));
}


if($row_HeaderInfo[5] < 3){
	$sql_DocInfo = 'select * from Progress where serialNumber="' . $bNo .'"';
	$result_DocInfo = mysqli_query($db,$sql_DocInfo);
	
	$check_discoveredNumber=1;
	$check_indicationNumber=1;
	$check_performCount=0;
	$check_requestCount=1;
	
	$set_discoveredNumber=1;
	$set_discoveredMatters="";
	$set_indicationNumber=1;
	$set_requestContents="";
	$set_requestCount=1;
	$set_performContents="";
	$set_performCount=0;
	
	while($row_DocInfo = mysqli_fetch_array($result_DocInfo)){
		if($check_requestCount == 1){
			$set_discoveredNumber=$row_DocInfo[1];
			$set_discoveredMatters=$row_DocInfo[2];
			$set_indicationNumber=$row_DocInfo[3];
			$set_requestContents=$row_DocInfo[4];
			$set_requestCount=$row_DocInfo[5];
			$check_requestCount ++;
		}
		if($check_discoveredNumber == $row_DocInfo[1]){
			if($check_indicationNumber == $row_DocInfo[3]){
				if($check_requestCount < $row_DocInfo[5]){
					$check_requestCount = $row_DocInfo[5];
				}
				if($check_performCount < $row_DocInfo[7]){
					$set_performContents = $row_DocInfo[6];
					$set_performCount = $row_DocInfo[7];
					
					$check_performCount = $row_DocInfo[7];
				}
			}
			else{
				array_push($result, array('discoveredNumbers'=>$set_discoveredNumber,'discoveredMatters'=>$set_discoveredMatters,'indicationNumbers'=>$set_indicationNumber,'requestContents'=>$set_requestContents,'requestCount'=>$set_requestCount,'performContents'=>$set_performContents,'performCount'=>$set_performCount));
				
				$set_indicationNumber=$row_DocInfo[3];
				$set_requestContents=$row_DocInfo[4];
				$set_requestCount=$row_DocInfo[5];
				$set_performContents=$row_DocInfo[6];
				$set_performCount=$row_DocInfo[7];
				
				$check_indicationNumber = $row_DocInfo[3];
				$check_performCount=0;
				$check_requestCount=1;
			}
			
		}
		else{
			array_push($result, array('discoveredNumbers'=>$set_discoveredNumber,'discoveredMatters'=>$set_discoveredMatters,'indicationNumbers'=>$set_indicationNumber,'requestContents'=>$set_requestContents,'requestCount'=>$set_requestCount,'performContents'=>$set_performContents,'performCount'=>$set_performCount));
			
			$set_discoveredNumber=$row_DocInfo[1];
			$set_discoveredMatters=$row_DocInfo[2];
			$set_indicationNumber=$row_DocInfo[3];
			$set_requestContents=$row_DocInfo[4];
			$set_requestCount=$row_DocInfo[5];
			$set_performContents=$row_DocInfo[6];
			$set_performCount=$row_DocInfo[7];
			
			$check_discoveredNumber = $row_DocInfo[1];
			$check_indicationNumber = 1;
			$check_performCount=0;
		}
	}
	array_push($result, array('discoveredNumbers'=>$set_discoveredNumber,'discoveredMatters'=>$set_discoveredMatters,'indicationNumbers'=>$set_indicationNumber,'requestContents'=>$set_requestContents,'requestCount'=>$set_requestCount,'performContents'=>$set_performContents,'performCount'=>$set_performCount));
}
else{
	//$sql_DocInfo = 'select * from DocBodyInfo where serialNumber="' . $bNo .'"';
	//$result_DocInfo = mysqli_query($db,$sql_DocInfo);
	//$row_DocInfo = mysqli_fetch_assoc($result_DocInfo);
}

echo json_encode($result);


?>