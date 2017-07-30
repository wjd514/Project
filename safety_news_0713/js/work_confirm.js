// JavaScript Document
var getParameters = function (paramName) {
    // 리턴값을 위한 변수 선언
    var returnValue;

    // 현재 URL 가져오기
    var url = location.href;

    // get 파라미터 값을 가져올 수 있는 ? 를 기점으로 slice 한 후 split 으로 나눔
    var parameters = (url.slice(url.indexOf('?') + 1, url.length)).split('&');

    // 나누어진 값의 비교를 통해 paramName 으로 요청된 데이터의 값만 return
    for (var i = 0; i < parameters.length; i++) {
        var varName = parameters[i].split('=')[0];
        if (varName.toUpperCase() === paramName.toUpperCase()) {
            returnValue = parameters[i].split('=')[1];
            return decodeURIComponent(returnValue);
        }
    }
};

function leadingZeros(n, digits) {
    var zero = '';
    n = n.toString();
 
    if (n.length < digits) {
        for (i = 0; i < digits - n.length; i++)
            zero += '0';
    }
    return zero + n;
}

if(getParameters('bno')){
	var bNo = getParameters('bno');
}
else{
	//alert("잘못된 요청");
	//history.back();
}
var check_indication_num = 0; //지시사항 갯수 저장
var check_requestCount = 0; //마지막 requestCount값 저장
var discoveredMatter = "";
var conduct = "";
var memberId='asdf';
//var bNo='1500910178989';
$.ajax({
	type: "GET",
	dataType: "json",
	url: 'http://ly.iptime.org/safety_news_0713/php/getDocInfo.php',
	data: {bNo:bNo,memberId:memberId},
	success: function (data) {
		//alert(data[1].workLocation);
		$('#subject').html(data[1].workLocation);
		$('#info').html(data[0].name+"<br>"+data[1].detectedTime);
		$('#discovered_content').html(data[2].discoveredMatters);
		discoveredMatter = data[2].discoveredMatters;
		conduct = data[1].conduct;
		var contents = "";
		var count = 1;
		if(data[1].progressState < 3){	// 진행사항이 완료가 아닌경우
			//var check_indication_num = 0; //지시사항 갯수 저장
			//var check_requestCount = 0; //마지막 requestCount값 저장
			var input_form_content = "";
			input_form_content +=  '<center><label class="label_form" for="getfile">사진추가 :  </label>';
      		input_form_content +=  '<input type="file"  multiple id="getfile" accept="image/*" name="images"/></input></center>';
			for(var i=2; i<data.length; i++){
				if(check_indication_num != data[i].indicationNumbers){
					if(contents != ""){
						contents += '</div>';
						contents += '<div class="textArea">';
						contents += '<center><textarea class="add_contents" id="add_contents'+(count-1)+'"></textarea>';
						contents += '<input type="button" class="submit" onclick="input_text('+(count-1)+');" value="입력"></input></center>';
						contents += '</div>';
					}
					contents += '<div class="indication" id="indication' + data[i].indicationNumbers + '">';
					contents += '<div class="subjects">지시사항' + data[i].indicationNumbers + '</div>';
					
					input_form_content += '<center><label class="label_form" for="send_msg">입력값'+count+' :  </label>';
					input_form_content += '<textarea name="indications" class="send_msg" id="send_msg'+data[i].indicationNumbers+'" disabled="true"></textarea></center>';
					check_indication_num = data[i].indicationNumbers;
					count++;
				}
				contents += '<div class="orderer">'+ data[i].requestContents + '</div>';
				if(data[i].performContents != null){
					contents += '<div class="performer">'+ data[i].performContents + '</div>';
				}
				if(check_requestCount < data[i].requestCount){
					check_requestCount = data[i].requestCount;

				}
			}
			contents += '</div>';
			contents += '<div class="textArea">';
			contents += '<center><textarea class="add_contents" id="add_contents'+(count-1)+'"></textarea>';
			contents += '<input type="button" class="submit" onclick="input_text('+(count-1)+');" value="입력"></input></center>';
			contents += '</div>';
			
			input_form_content += '<center><input type="button" id="submit" value="전송" onClick="send_form();"></input>';
			input_form_content += '<input type="button" id="cancel" value="취소" onClick="cancels();"></input></center>';
			
			$("#indications").html(contents);
			$("#input_form").html(input_form_content);
			
			if(data[1].conduct == "orderer"){
				if(data[1].progressState == 2){
					var complete = '<center><input type="button" id="complete" value="완료" onClick="complete_doc();"></input></center>';
					//alert(data[1].progressState);
					$("#complete_document").html(complete);	
					
				}else{
					jQuery(".textArea").hide();
				}
				//alert(data[1].progressState);
				check_requestCount++;
			}else{
				if(data[1].progressState == 2){
					jQuery(".textArea").hide();
				}
			}
			
			
			
		}else{	//진행사항이 완료일 경우
			
		}
	},
	
	error: function (request,status,error) {
		console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	}
});



function input_text(n){
	if($("#add_contents"+n).val().length > 0){
		if($("#input_form").css("display")=="none"){
			jQuery("#input_form").show();
		}
		var text = $("#add_contents"+n).val();
		$("#send_msg"+n).val(text);
	}else{
		//alert("내용을 입력해 주세요");
		alert(n);
	}
}

function complete_doc(){
	$.ajax({
		type: "POST",
		dataType: "json",
		url: 'http://ly.iptime.org/safety_news_0713/php/finishDoc.php',
		data: {bNo:bNo},
		success: function (data) {
			alert("문서가 완료되었습니다.");
			history.back();
		},

		error: function (request,status,error) {
			console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
}
function send_form(){
	var file = $('#getfile').val();
	
	var formdata = new FormData($('#form').val()[0]);
	formdata.append("images[]", file.files);
	formdata.append("serialNumber", bNo);
	formdata.append("conduct", conduct);
	formdata.append("discoveredMatter", discoveredMatter);
	formdata.append("indicationNum", check_indication_num);
	formdata.append("requestCount", check_requestCount);
	for(var i=0; i<check_indication_num; i++){
		formdata.append("reply[]", $("#send_msg"+(i+1)).val());
	}
	
	//formdata.submit();
	$.ajax({
		type: "POST",
		processData: false,
		contentType: false,
		url: 'http://ly.iptime.org/safety_news_0713/php/insertPerform.php',
		data: formdata,
		success: function (result) {
			alert("작업지시가 등록되었습니다.");
			history.back();
			//alert(result[0]['conduct']);
		},
		error: function (request,status,error) {
			console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
}
function cancels(){
	form.reset();
	jQuery("#input_form").hide();
	/*
	for(var i=0; i<check_indication_num; i++){
		$("#send_msg"+(i+1)).val('');
	}
	*/
	
	
}























