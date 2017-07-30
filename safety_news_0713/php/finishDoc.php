<?php
	require_once("../../new_head/safety_news_db_0713.php");
	header("Content-Type: application/javascript; charset=utf-8");
	header("Access-Control-Allow-Origin:*");
	header('Access-Control-Allow-Methods: GET, POST, PUT');
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
	
	
	mysqli_query($conn,"set session character_set_connection=utf8;");
	mysqli_query($conn,"set session character_set_results=utf8;");
	mysqli_query($conn,"set session character_set_client=utf8;");
	mysqli_set_charset($db,"utf8");
	
	$serialNumber = $_POST['bNo'];
	$discoveredNumbers = 1;
	$finishedTime = Date("Y-m-d H:i:s");
	
	//requestCount가 가장 마지막인것을 가져온다
	$sql_desc  = 'SELECT * FROM `Progress` WHERE serialNumber=' .$serialNumber. ' and discoveredNumbers=' .$discoveredNumbers. ' ORDER BY requestCount desc';
	$result_desc = mysqli_query($db,$sql_desc);
	
	$discoveredMatter = "";
	$requestContents = array();
	$performContents = array();
	$indications = "";
	$boundary = "*****";
	
	//만약 requestCount가 1이면 모두 저장 아니면 수행한 내용만 저장
	//discoveredMatter은 공통으로 한번 저장
	while($row_desc = mysqli_fetch_array($result_desc)){
		if(!$discoveredMatter){
			$discoveredMatter = $row_desc[2];
		}
		if(strcmp($row_desc[5],"1")==0){
			array_push($requestContents,$row_desc[4]);
			array_push($performContents,$row_desc[6]);
		}else{
			array_push($performContents,$row_desc[6]);
		}
	}
	
	//위에서 requestContents을 저장 안했으면 requestCount가 1인 값을 가져와 저장
	if(!$requestContents[0]){
		$sql_asc  = 'SELECT requestContents FROM `Progress` WHERE serialNumber=' .$serialNumber. ' and discoveredNumbers=' .$discoveredNumbers. ' ORDER BY requestCount';
		$result_asc = mysqli_query($db,$sql_asc);
		
		while($row_asc = mysqli_fetch_array($result_asc)){
			array_push($requestContents,$row_asc[0]);
		}
		
	}
	
	//저장한 결과값을 bundary를 넣어 한번에 저장
	for($i=0; $i<count($requestContents); $i++){
		
		if($i !=0){
			$indications = $indications . $boundary;
		}
		$indications = $indications . $requestContents[$i] . $boundary . $performContents[$i];
	}
	
	//DocBodyInfo 테이블에 저장
	$data = "'" .$serialNumber. "','" .$discoveredNumbers. "','" .$discoveredMatter. "','" .$indications. "'";
	$sql = "insert into DocBodyInfo(`serialNumber`,`discoveredNumbers`,`discoveredMatters`,`indications`) values (".$data.")";
	$result = mysqli_query($db,$sql);
	
	//DocBodyInfo에 저장 성공 시 Progress테이블에 내용 삭제 및 Works_List의 progressState 3으로 변경
	if($result){
		$sql_delete = "delete from Progress where serialNumber='" .$serialNumber. "'";
		$result_delete = mysqli_query($db,$sql_delete);
		
		$sql_update = "update Works_List set progressState= 3, finishedTime='" .$finishedTime. "' where serialNumber='" .$serialNumber. "'";
		$result_update = mysqli_query($db,$sql_update);
	}
	
	
	//$result = array();
	//array_push($result,array('dis'=>$discoveredMatter));
	//array_push($result,array('indications'=>$indications));
	echo json_encode($result);
?>