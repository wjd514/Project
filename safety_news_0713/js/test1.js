var file = document.querySelector('#getfile');
//var file = $("#getfile").val("");


file.onchange = function () {
    var fileList = file.files ;
	var fileNum = fileList.length; //file갯수
	var loadcount = 0; // 이미지 onload 카운트
	var tempImage = new Array();
	var reader = new Array();
	var cnt = 0;
	
	var canvas = document.createElement('canvas');
	var canvasContext = canvas.getContext("2d");
	canvas.width = 200 * fileNum;
	canvas.height = 200;
	
	for(var i=0; i<fileNum; i++){
		reader[i] = new FileReader();
		reader[i].readAsDataURL(fileList [i]);
		
		if(i==fileNum -1){
			imageload(cnt);
		}
	}
	/*
	function imageload(cnt){
		reader[cnt].onload = function  () {
			tempImage[cnt] = new Image();
			tempImage[cnt].src = reader[cnt].result;
			tempImage[cnt].onload = function (){
				loadcount++;
				
				cnt++;
				
				if(cnt != fileNum){
					alert(reader[cnt].result);
					imageload(cnt);
				}				
			}
		}
	}
	*/
	function imageload(cnt){
		reader[0].onload = function  () {
			tempImage[0] = new Image();
			tempImage[0].src = reader[0].result;
			tempImage[0].onload = function (){
				loadcount++;
			}
		}
		reader[1].onload = function  () {
			tempImage[1] = new Image();
			tempImage[1].src = reader[1].result;
			tempImage[1].onload = function (){
				loadcount++;
			}
		}
		reader[2].onload = function  () {
			tempImage[2] = new Image();
			tempImage[2].src = reader[2].result;
			tempImage[2].onload = function (){
				loadcount++;
			}
		}
	}
	
	var timer = setInterval(function(){
		if(loadcount == fileNum){
			alert("w");
			clearInterval(timer);
			draw();
		}
	},100);
    
	function draw(){
		if(loadcount != fileNum){
			alert("e");
			canvasContext = "30px arial";
			canvasContext.fillText("로딩중...",100,100);
		}else{
			alert("d");
			var fileName = "";
			for(var i=0; i<fileNum; i++){
				canvasContext.drawImage(tempImage[i], i*200, 0, 200, 200);
				fileName += fileList [i].name + '\n'
			}
			//캔버스에 그린 이미지를 다시 data-uri 형태로 변환
			var dataURI = canvas.toDataURL("image/jpeg");
			//썸네일 이미지 보여주기
			document.querySelector('#thumbnail').src = dataURI;
			
			//썸네일 이미지를 다운로드할 수 있도록 링크 설정
            //document.querySelector('#download').href = dataURI;
			
			
			
			document.getElementById("imgs").value= fileName;
		}
	}
	
			
	/*
	reader.onload = function  () {
        //로컬 이미지를 보여주기
        //document.querySelector('#preview').src = reader.result;
		
        //썸네일 이미지 생성
        tempImage = new Image(); //drawImage 메서드에 넣기 위해 이미지 객체화
        tempImage.src = reader.result; //data-uri를 이미지 객체에 주입
		
        tempImage.onload = function () {
			loadcount++;
		};
	};
	reader1.onload = function  () {
        //로컬 이미지를 보여주기
        //document.querySelector('#preview').src = reader.result;
		
        //썸네일 이미지 생성
        tempImage1 = new Image(); //drawImage 메서드에 넣기 위해 이미지 객체화
        tempImage1.src = reader1.result; //data-uri를 이미지 객체에 주입
		
        tempImage1.onload = function () {
			loadcount++;
		};
	};
	
	reader2.onload = function  () {
        //로컬 이미지를 보여주기
        //document.querySelector('#preview').src = reader.result;
		
        //썸네일 이미지 생성
        tempImage2 = new Image(); //drawImage 메서드에 넣기 위해 이미지 객체화
        tempImage2.src = reader2.result; //data-uri를 이미지 객체에 주입
		
        tempImage2.onload = function () {
			loadcount++;
		};
	};
	
	var timer = setInterval(function(){
		if(loadcount == 3){
			clearInterval(timer);
			draw();
		}
	},100);
	
	function draw(){
		if(loadcount != 3){
			canvasContext = "30px arial";
			canvasContext.fillText("로딩중...",100,100);
		}else{
			canvasContext.drawImage(tempImage, 0, 0, 200, 200);
			canvasContext.drawImage(tempImage1, 200, 0, 200, 200);
			canvasContext.drawImage(tempImage2, 400, 0, 200, 200);
			
			//캔버스에 그린 이미지를 다시 data-uri 형태로 변환
			var dataURI = canvas.toDataURL("image/jpeg");
			//썸네일 이미지 보여주기
			document.querySelector('#thumbnail').src = dataURI;
			
			//썸네일 이미지를 다운로드할 수 있도록 링크 설정
            //document.querySelector('#download').href = dataURI;
			
			document.getElementById("imgs").value=fileList [0].name + '\n' + fileList [1].name + '\n' +  fileList [2].name;
		}
	}
	*/
	/*
	// 읽기
    var reader = new FileReader();
    reader.readAsDataURL(fileList [0]);
	
	var reader1 = new FileReader();
    reader1.readAsDataURL(fileList [1]);

	var reader2 = new FileReader();
    reader2.readAsDataURL(fileList [2]);
		
	
	var loadcount = 0;
	var canvas = document.createElement('canvas');
	var canvasContext = canvas.getContext("2d");
	var tempImage;
	var tempImage1;
	var tempImage2;
	
	
	//캔버스 크기 설정
	canvas.width = 600; //가로 200px
	canvas.height = 200; //세로 200px
	
	//var img = '<p>';
	//img += fileList [0].name;
	//img += '</p><br><p>';
	//img += fileList [1].name;
	//img += '</p>';
	//document.querySelector('#imgs').src = fileList [0].name;
	
	
    //로드 한 후
    reader.onload = function  () {
        //로컬 이미지를 보여주기
        //document.querySelector('#preview').src = reader.result;
		
        //썸네일 이미지 생성
        var tempImage = new Image(); //drawImage 메서드에 넣기 위해 이미지 객체화
        tempImage.src = reader.result; //data-uri를 이미지 객체에 주입
		
        tempImage.onload = function () {
			//리사이즈를 위해 캔버스 객체 생성
			var canvas = document.createElement('canvas');
			var canvasContext = canvas.getContext("2d");

			//캔버스 크기 설정
			canvas.width = 200; //가로 200px
			canvas.height = 200; //세로 200px
			
            //이미지를 캔버스에 그리기
            canvasContext.drawImage(tempImage, 0, 0, 200, 200);
			
			//캔버스에 그린 이미지를 다시 data-uri 형태로 변환
			var dataURI = canvas.toDataURL("image/jpeg");
			//썸네일 이미지 보여주기
			document.querySelector('#thumbnail').src = dataURI;
            
            
		
        };
    };
	*/
	
	//document.getElementById("imgs").value=fileList [0].name + '\n' + fileList [1].name + '\n' +  fileList [2].name;
};



