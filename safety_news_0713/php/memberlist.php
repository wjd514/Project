<?php
	require_once("../../new_head/safety_news_db_0713.php");
	header("Content-Type: application/javascript; charset=utf-8");
	header("Access-Control-Allow-Origin:*");
	header('Access-Control-Allow-Methods: GET, POST, PUT');
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
	
	mysqli_query($db,"set session character_set_connection=utf8;");
	mysqli_query($db,"set session character_set_results=utf8;");
	mysqli_query($db,"set session character_set_client=utf8;");	
	mysqli_set_charset($db,"utf8");
	
	$memberId = $_GET['memberId'];
	
	$sql = 'select * from MemberInfo where NOT id="' .  $memberId . '"';
	$result = mysqli_query($db,$sql);
	$memberlist = array();
	while($row = mysqli_fetch_array($result)){
		array_push($memberlist,array('phoneNumber'=>$row[0],'id'=>$row[1],'name'=>$row[3],'company'=>$row[4],'position'=>$row[5],'section'=>$row[6]));
	}
	
	echo json_encode($memberlist);
	
?>