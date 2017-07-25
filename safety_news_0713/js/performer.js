// JavaScript Document

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
		
		var contents = "";
		if(data[1].progressState < 3){
			var check_indication_num = 0;
			var check_requestCount = 0;
			for(var i=2; i<data.length; i++){
				if(check_indication_num != data[i].indicationNumbers){
					if(contents != ""){
						contents += '</div>';
						contents += '<div class="input_form">';
						contents += '<center><input type="text" class="add_contents"></input>';
						contents += '<input type="button" class="submit" value="전송"></input></center>';
						contents += '</div>';
					}
					contents += '<div class="indication" id="indication"' + data[i].indicationNumbers + '>';
					contents += '<div class="subjects">지시사항' + data[i].indicationNumbers + '</div>';
					check_indication_num = data[i].indicationNumbers;
					if(check_requestCount < data[i].requestCount){
						check_requestCount = i;
					}
				}
				contents += '<div class="orderer">'+ data[i].requestContents + '</div>';
				if(data[i].performContents != null){
					contents += '<div class="performer">'+ data[i].performContents + '</div>';
				}
			}
			contents += '</div>';
			contents += '<div class="input_form">';
			contents += '<center><input type="text" class="add_contents"></input>';
			contents += '<input type="button" class="submit" value="전송"></input></center>';
			contents += '</div>';
			if(data[1].conduct == "orderer"){
				var complete = '<center><input type="buttron" id="complete" value="완료" onClick="complete_doc()"></input></center>';
			}
			$("#indications").html(contents);
		}
	},
	
	error: function (request,status,error) {
		console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	}
});



























