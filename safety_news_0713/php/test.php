<?php
	include_once '../../new_head/config_reg.php';
	header("Access-Control-Allow-Origin:*");
	header('Access-Control-Allow-Methods: GET, POST, PUT');
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	mysqli_query($conn,"set session character_set_connection=utf8;");
	mysqli_query($conn,"set session character_set_results=utf8;");
	mysqli_query($conn,"set session character_set_client=utf8;");
	
	$discoveredMatter = $_POST['discoveredMatter'];
	$indications = $_POST['indications'];
	
	print_r($_FILES["images"]);
	echo "\n";
	//���ϼ���
	foreach ($_FILES["images"]["error"] as $key => $error) {
		
		if ($error == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["images"]["tmp_name"][$key];
			$name = $_FILES["images"]["name"][$key];
			
			if(strcmp($_FILES["images"]["type"][$key],"image/png") == 0){
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
			//������ �Ϻκи� ���۵�
			echo "upload partial";
		}
		else if($error == UPLOAD_ERR_NO_FILE){
			//������ ���۵��� ����
			echo "upload fail";
		}
		else if($error == UPLOAD_ERR_PARTIAL){
			//��ũ�� ���� ���� ����
			echo "upload cant write";
		}
		else if($error == UPLOAD_ERR_PARTIAL){
			//���ε� ������
			echo "upload error";
		}
	}
	echo $discoveredMatter;
?>