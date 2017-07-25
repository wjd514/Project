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
	alert("잘못된 요청");
	history.back();
}
var memberId='aa';
$.ajax({
	type: "GET",
	dataType: "json",
	url: 'http://ly.iptime.org/safety_news_0713/php/getDocInfo.php',
	data: {bNo:bNo,memberId:memberId},
	success: function (data) {
		//alert(data[0]['name']);
		var MemberInfo = '<table class="MemberTable">';
		MemberInfo += '<tr>';
		MemberInfo += '<td class="td1">이름</td>';
		MemberInfo += '<td class="td1" colspan="6">';
		MemberInfo += data[0]['name'];
		MemberInfo += '</td>'
		MemberInfo += '</tr>';
		
		MemberInfo += '<tr>';
		MemberInfo += '<td class="td1">소속</td>';
		MemberInfo += '<td class="td1">';
		MemberInfo += data[0]['company'];
		MemberInfo += '</td>'
		MemberInfo += '<td class="td1">직책</td>';
		MemberInfo += '<td class="td1">';
		MemberInfo += data[0]['position'];
		MemberInfo += '</td>'
		MemberInfo += '<td class="td1">직급</td>';
		MemberInfo += '<td class="td1">';
		MemberInfo += data[0]['section'];
		MemberInfo += '</td>'
		MemberInfo += '</tr>';
		
		MemberInfo += '</table>';
		$("#memberInfo").html(MemberInfo);
		
		var DocInfo = '<table class="DocInfo">';
		DocInfo += '<tr>';
		DocInfo += '<td class="td2" colspan="4">작성요약</td>';
		DocInfo += '</tr>';
		
		DocInfo += '<tr>'; 
		DocInfo += '<td class="td1">지시자</td> <td class="td1">';
		DocInfo += data[1]['conduct'];
		DocInfo += '</td>';
		DocInfo += '<td class="td1">작업자</td> <td class="td1">';
		DocInfo += data[1]['conduct'];
		DocInfo += '</td>';
		DocInfo += '</tr>';
		
		DocInfo += '<tr>'; 
		DocInfo += '<td class="td1">발견시간</td> <td class="td1">';
		DocInfo += data[1]['detectedTime'];
		DocInfo += '</td>';
		DocInfo += '<td class="td1">완료시간</td> <td class="td1">';
		if(!data[1]['finishedTime']){
			DocInfo += "미완료";
		}else{
			DocInfo += data[1]['finishedTime'];	
		}
		DocInfo += '</td>';
		DocInfo += '</tr>';
		
		DocInfo += '<tr>';
		DocInfo += '<td class="td1">진행사항</td> <td class="td1" colspan="3">';
		if(data[1]['progressState'] == 0){
			DocInfo += "작업지시";
		}else if(data[1]['progressState'] == 1){
			DocInfo += "진행중";
		}else if(data[1]['progressState'] ==2){
			DocInfo += "재지시";
		}else{
			DocInfo += "완료";
		}
		DocInfo += '</td>';
		DocInfo += '</tr>';
		
		DocInfo += '</table>';
		$("#DocInfo").html(DocInfo);
		
		var check_dicoverNumber =0;
		var DiscoveredMatter = "";
		for(var i=2; i<data.length; i++){
			if(check_dicoverNumber != data[i]['discoveredNumbers']){
				if(i != 2){
					DiscoveredMatter += '</div>';
				}
				DiscoveredMatter += '<div class="Matters">';
				DiscoveredMatter +=  '<table class="discoveredMatter">';
				DiscoveredMatter +=  '<tr>'; 
				DiscoveredMatter +=  '<td class="td1">문제발견';
				DiscoveredMatter += data[i]['discoveredNumbers'];
				DiscoveredMatter += '</td>';
				DiscoveredMatter += ' <td class="td1" colsapn="3">';
				DiscoveredMatter +=  data[i]['discoveredMatters'];
				DiscoveredMatter += '</table>';
				check_dicoverNumber = data[i]['discoveredNumbers'];
				//DiscoveredMatter += '</div>';	
			}
			DiscoveredMatter += '<table class="indications">';
			DiscoveredMatter += '<tr>';
			DiscoveredMatter += '<td class="td1">문제사진</td> <td class="td1">지시사항';
			DiscoveredMatter += data[i]['indicationNumbers'];
			DiscoveredMatter += '</td>';
			DiscoveredMatter += '</tr>';
			DiscoveredMatter += '<tr>';
			DiscoveredMatter += '<td class="indicationImgs"><img src="http://ly.iptime.org/photo/Aegis_1483421009387.jpg" class="imgs"></td>';
			DiscoveredMatter += '<td class="td1">';
			DiscoveredMatter += data[i]['requestContents'];
			DiscoveredMatter += '</td>';
			DiscoveredMatter += '</tr>';
			DiscoveredMatter += '<tr>';
			DiscoveredMatter += '<td class="td1">해결사진</td> <td class="td1">수행사항</td>';
			DiscoveredMatter += '</tr>';
			DiscoveredMatter += '<tr>';
			DiscoveredMatter += '<td class="indicationImgs"><img src="http://ly.iptime.org/photo/Aegis_1483421009387.jpg" class="imgs"></td>';
			DiscoveredMatter += '<td class="td1">';
			if(!data[i]['performContents']){
				DiscoveredMatter += "미완료";
			}else{
				DiscoveredMatter += data[i]['performContents'];
			}			
			DiscoveredMatter += '</td>';
			DiscoveredMatter += '</tr>';
			DiscoveredMatter += '</table>';
		}
		DiscoveredMatter += '</div>';
		$("#content").html(DiscoveredMatter);
		
	},
	
	error: function (request,status,error) {
		console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	}
});






























