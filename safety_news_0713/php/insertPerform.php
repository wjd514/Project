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
	
	$serialNumber = $_POST['serialNumber'];
	$conduct = $_POST['conduct'];
	$discoveredMatter = $_POST['discoveredMatter']; //재지시에만 필요 conduct가 excutor이면 필요없음
	$indicationNum = $_POST['indicationNum']; // 배열
	$requestCount = $_POST['requestCount'];
	$reply = $_POST['reply']; //배열
	
	$discoveredNumbers = 1;
	$image_firstName = "Aegis_";
	$image_middleName = "P_";
	
	$i = 1;
	//conduct가 orderer이면 새로 삽입 excutor이면 수정
	foreach($reply as $key => $value){
		if(strcmp($conduct,"orderer")==0){
			$data_stream_Progress[$key] = "'" .$serialNumber. "','" .$discoveredNumbers. "','" .$discoveredMatter. "','" . ($key+1) . "','" .$value. "','" .$requestCount. "'";
			
			$query = "insert into Progress(`serialNumber`, `discoveredNumbers`,`discoveredMatters`,`indicationNumbers`,`requestContents`,`requestCount`) values (".$data_stream_Progress[$key].")";
			
			$sql_update = 'update Works_List set progressState= 1 where serialNumber="' .$serialNumber. '"';
			echo strcmp($conduct,"orderer");
		
		}else{
			$query = "update Progress set performContents='" .$value . "', performCount= 1 where serialNumber='" .$serialNumber. "' and discoveredNumbers= '" .$discoveredNumbers. "' and indicationNumbers= '" .($key+1). "' and requestCount= '" .$requestCount. "'";
			$sql_update = 'update Works_List set progressState= 2 where serialNumber="' .$serialNumber. '"';
		}
		$result_Progress[$key] = mysqli_query($db, $query);
		echo $query;
		
		$i++;
	}
	$result_update = mysqli_query($db,$sql_update);
	
	$img_name = "";
	foreach ($_FILES["images"]["error"] as $key => $error) {
		
		if ($error == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["images"]["tmp_name"][$key];
			//$name = $_FILES["images"]["name"][$key];
			$strTok = explode('.',$_FILES["images"]["name"][$key]);
			$name = $image_firstName . $image_middleName . $serialNumber . "_" . ($key+1) . "." . $strTok[1];
			if($key == 0){
				$img_name = $name;
			}else{
				$img_name += "," . $name;
			}
			$type = $_FILES["images"]["type"][$key];
			
			if(strcmp($type,"image/png") == 0 || strcmp($type,"image/jpeg") == 0){
				move_uploaded_file($tmp_name, "../../photo/$name");
			}
			/*
			$strTok = explode('.',$name);
			if(strcmp($strTok[1],"jpg") == 0 || strcmp($strTok[1],"png") == 0 ){
				echo "1";	
				move_uploaded_file($tmp_name, "../../photo/$name");
			}
			else if(strcmp($strTok[1],"pdf") == 0){
				move_uploaded_file($tmp_name, "../pdf/$name");
			}
			else{
				move_uploaded_file($tmp_name, "../data/$name");
			}
			*/
		}
		else if($error == UPLOAD_ERR_PARTIAL){
			//파일이 일부분만 전송됨
			echo "upload partial";
		}
		else if($error == UPLOAD_ERR_NO_FILE){
			//파일이 전송되지 않음
			echo "upload fail";
		}
		else if($error == UPLOAD_ERR_PARTIAL){
			//디스크에 파일 쓰기 실패
			echo "upload cant write";
		}
		else if($error == UPLOAD_ERR_PARTIAL){
			//업로드 중지됨
			echo "upload error";
		}
	}
	
	$result = array();
	array_push($result,array('conduct' => $result_update ));
	//array_push($result,array('indications'=>$indications));
	echo json_encode($result);
?>