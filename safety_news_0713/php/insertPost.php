<?php
	require_once("../../new_head/safety_news_db_0713.php");
	require_once("./thumnail.php");
	header("Content-Type: application/javascript; charset=utf-8");
	header("Access-Control-Allow-Origin:*");
	header('Access-Control-Allow-Methods: GET, POST, PUT');
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
	
	
	mysqli_query($conn,"set session character_set_connection=utf8;");
	mysqli_query($conn,"set session character_set_results=utf8;");
	mysqli_query($conn,"set session character_set_client=utf8;");
	mysqli_set_charset($db,"utf8");
	
	$id = $_POST['id'];
	$separate = $_POST['separate'];
	$executor = $_POST['executor'];
	$discoveredMatter = $_POST['discoveredMatter'];
	$indications = $_POST['indications'];
	
	$serialNumber = $_POST['serialNumber'];
	$discoveredNumbers = "1";
	$requestCount = "1";
	$progressState = "0";
	$image_firstName = "Aegis_";
	$image_middleName = "DM_";
	
	//Works_On 데이터 등록
	$data_orderer_Works_On = "'" .$serialNumber. "','" .$id. "','orderer','" .$separate. "'";
	
	$sql_orderer = "insert into Works_On(`serialNumber`,`id`,`conduct`,`workLocation`) values (".$data_orderer_Works_On.")";
	$result_orderer_WorkList = mysqli_query($db,$sql_orderer);
	
	$data_executor_Works_On = "'" .$serialNumber. "','" .$executor. "','executor','" .$separate. "'";
	
	$sql_executor = "insert into Works_On(`serialNumber`,`id`,`conduct`,`workLocation`) values (".$data_executor_Works_On.")";
	$result_executor_WorkList = mysqli_query($db,$sql_executor);
	
	//Works_List 데이터 등록
	$data_Works_List = "'" .$serialNumber. "'";
	
	$sql = "insert into Works_List(`serialNumber`) values (".$data_Works_List.")";
	$result_executor_WorkList = mysqli_query($db,$sql);
	
	//progress 데이터 입력 시작
	foreach($indications as $key => $value){
		$indicationNum = $key + 1;
		$data_stream_Progress[$key] = "'" .$serialNumber. "','" .$discoveredNumbers. "','" .$discoveredMatter. "','" . $indicationNum . "','" .$indications[$key]. "','" .$requestCount. "'";
		
		$query = "insert into Progress(`serialNumber`, `discoveredNumbers`,`discoveredMatters`,`indicationNumbers`,`requestContents`,`requestCount`) values (".$data_stream_Progress[$key].")";
		
		$result_Progress[$key] = mysqli_query($db, $query);
	}
	
	//$img_name = "";
	//$img_thum_name = "";
	$img_num = 0;
	foreach ($_FILES["images"]["error"] as $key => $error) {
		
		if ($error == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["images"]["tmp_name"][$key];
			//$name = $_FILES["images"]["name"][$key];
			$strTok = explode('.',$_FILES["images"]["name"][$key]);
			$name = $image_firstName . $image_middleName . $serialNumber . "_" . ($key) . "." . $strTok[1];
			$thum_name = $image_firstName . "thum_" . $image_middleName . $serialNumber . "_" . ($key) . "." . $strTok[1];
			$img_num++;
			
			$type = $_FILES["images"]["type"][$key];
			
			if(strcmp($type,"image/png") == 0 || strcmp($type,"image/jpeg") == 0){
				move_uploaded_file($tmp_name, "../../photo/$name");
			}
			getThumb("../../photo/$name","../../photo/$thum_name",300,300);
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
	//if($img_num == 0){
		//$img_name = "no_img";
		//$img_thum_name = "no_img";
	//}else{
		//$img_name = $img_name. "," .$img_num;
		//$img_thum_name = $img_thum_name. "," .$img_num;
	//}
	$data_img = "'" .$serialNumber. "','" .$img_num. "'";
	$sql_img = "insert into PhotoInfo(`serialNumber`, `discoveredMatterPhotoNum`) values (" .$data_img. ")";
	$result_img = mysqli_query($db,$sql_img);
	
	$result = array();
	//array_push($result,array('dis'=>$discoveredMatter));
	array_push($result,array('indications'=>$indications));
	echo json_encode($result);
?>