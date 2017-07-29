
//사진관련
var file = document.querySelector('#getfile');

var fileNum = 0;
var formData = new FormData(document.querySelector('#form')[0]);
var tempImage = new Array();
var ImageName = new Array();

file.onchange = function () {
    var fileList = file.files ;
	formData.append("images[]", fileList[0]);
	
    // 읽기
    var reader = new FileReader();
    reader.readAsDataURL(fileList [0]);

    //로드 한 후
    reader.onload = function  () {
        //썸네일 이미지 생성
		if(!fileList [0].name){
			ImageName[fileNum] = fileNum;
		}else{
			ImageName[fileNum] = fileList [0].name;
		}
		
        tempImage[fileNum] = new Image(); //drawImage 메서드에 넣기 위해 이미지 객체화
        tempImage[fileNum].src = reader.result; //data-uri를 이미지 객체에 주입
        tempImage[fileNum].onload = function () {
            fileNum++;
        };
    };
};

document.getElementById('imagefin').onclick= function() {
	var canvas = document.createElement('canvas');
	var canvasContext = canvas.getContext("2d");
	//캔버스 크기 설정
	canvas.width = 300*fileNum; //가로 100px
	canvas.height = 300; //세로 100px
	
	var fileName="";
	for(var i=0; i<fileNum; i++){
		canvasContext.drawImage(tempImage[i], i*300, 0, 300, 300);
		fileName += ImageName[i] + '\n'
	}
	//캔버스에 그린 이미지를 다시 data-uri 형태로 변환
	var dataURI = canvas.toDataURL("image/jpeg");

	//썸네일 이미지 보여주기
	document.getElementById('thumbnail').style.display = 'block';
	document.getElementById('imgs').style.display = 'block';
	document.querySelector('#thumbnail').src = dataURI;
	document.getElementById("imgs").value= fileName;
	
}
//끝

//지시사항 추가삭제
var arrInput = new Array(2);
var arrInputValue = new Array(2);
 
function addInput() {
  arrInput.push(arrInput.length);
  arrInputValue.push("");
  display();
}
 
function display() {
  document.getElementById('parah').innerHTML="";
  for (intI=2;intI<arrInput.length;intI++) {
    document.getElementById('parah').innerHTML+=createInput(arrInput[intI], arrInputValue[intI]);
  }
}
 
function saveValue(intId,strValue) {
  arrInputValue[intId]=strValue;
}  
 
function createInput(id,value) {
  return "<label class='label_separate' for=" + id + ">지시사항"+id+" </label><textarea class='indication' id='indication"+ id +"' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"'></textarea><br>";
}
 
function deleteInput() {
  if (arrInput.length > 2) { 
     arrInput.pop(); 
     arrInputValue.pop();
  }
  display(); 
}
//끝
//실행자 찾기 결과값 리턴
var executorInfo ="";
function memberSelect() {
	window.open("http://ly.iptime.org/safety_news_0713/member.html","_blank","width=400,height=360,resizable=no,directories=no,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes");
}
function setChildValue(name){
	executorInfo = name;
	document.getElementById('executor').value = name.name;
}
//끝

function sendInfo(){
	//alert(ImageFile[0][0].name);
	var serialNumber = new Date().getTime();
	formData.append("serialNumber", serialNumber);
	formData.append("id", "asdf");
	formData.append("separate", document.getElementById('separate').value);
	formData.append("executor", executorInfo.id);
	formData.append("discoveredMatter", document.getElementById('DM').value);
	formData.append("indications[]", document.getElementById('indication1').value);
	for(var i=2; i<arrInput.length; i++){
		//alert(arrInputValue[i]);
		formData.append("indications[]", arrInputValue[i]);
	}
	/*
	console.log(formData);
	var xhr = XMLHttpRequest();
	xhr.open('post','http://ly.iptime.org/dd/php/test.php',true);
	xhr.send(formData);
	*/
	$.ajax({
		type: "POST",
		processData: false,
		contentType: false,
		url: 'http://ly.iptime.org/safety_news_0713/php/insertPost.php',
		data: formData,
		success: function (result) {
			alert("작업지시가 등록되었습니다.");
		},
		error: function (request,status,error) {
			console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
	
	$("form").each(function(){
		this.reset();
		document.getElementById('thumbnail').style.display = 'block';
		document.getElementById('imgs').style.display = 'block';
		while(arrInput.length > 2){
			deleteInput();
		}
	});
	
	/*
	var data = new Array();
	var info = new Object();
	var indications = new Array();
	var indication = new Object();
	
	info.separate = document.getElementById('separate').value;
	info.executor = executorInfo.id;
	info.discoveredMatter = document.getElementById('DM').value;
	data.push(info);
	
	indication.indication = document.getElementById('indication1').value;
	indications.push(indication);
	
	for(var i=2; i<arrInput.length; i++){
		indication.indication = arrInputValue[i];
		indications.push(indication);
	}
	
	data.push(indications);
	*/
	//alert(formData["separate"]);
	
}