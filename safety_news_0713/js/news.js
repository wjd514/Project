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


$(function() {
	$( "#setDate" ).datepicker({
		//changeMonth: true, 
		//changeYear: true,
		dateFormat: "yy-mm-dd",
		yearSuffix: '년',
		showMonthAfterYear: true,
		dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
		dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], 
		monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		
		onSelect: function (dateText, inst) {
			location.href = "./board.html?searchDate="+dateText;
		}
	});
});

$(document).ready(function(){
	$("#setDate").val(searchDate);
});
$(document).ready(function(){
	$("#prev").click(function(){
		location.href = "./board.html?searchDate="+prev;
	});
});
$(document).ready(function(){
	$("#next").click(function(){
		location.href = "./board.html?searchDate="+next;
	});
});

var memberName = 'asdf';

if(!getParameters('page')){
	var page = 1;
}else{
	var page = getParameters('page');
}


if(!getParameters('searchDate')){
	var dt = new Date();
	var year = dt.getFullYear();
	var month = dt.getMonth()+1;
	var day = dt.getDate();
	var searchDate = year + '-' + month + '-' + day;
}
else{
	var searchDate = getParameters('searchDate');
}

var prevday = new Date(searchDate);	
var nextday = new Date(searchDate);	
prevday.setDate(prevday.getDate() -1);
nextday.setDate(nextday.getDate() +1);

var prev = prevday.getFullYear() + '-' + leadingZeros(prevday.getMonth()+1,2) + '-' + leadingZeros(prevday.getDate(),2);
var next = nextday.getFullYear() + '-' + leadingZeros(nextday.getMonth()+1,2) + '-' + leadingZeros(nextday.getDate(),2);


var subString= '&searchDate=' + searchDate;
var emptyData = "";
var allPage;
var onePage = 6; //한페이지에 보여줄 게시글의 수
var currentLimit = (onePage * page) - onePage;
//var sqlLimit = 'limit ' + currentLimit + ', ' + onePage;

if(getParameters('searchColumn')){
	var searchColumn = getParameters('searchColumn');
	subString += '&searchColumn=' + searchColumn;
}

if(getParameters('searchText')){
	var searchText = getParameters('searchText');
	subString += '&searchText=' + searchText;
}

if(!searchColumn && !searchText){
	var searchSql = ' where writer = "' + memberName + '" and detectedTime = "' + searchDate + '"';	
}
// where writer = "정신" and detectedTime = "2017-07-11" 
else{
	var searchSql = ' where ' + searchColumn + ' like "%' + searchText + '%" and writer = "' + memberName + '" and creationDate = "' + searchDate + '"';
}
			
var allPost;
//var jobj = new Object();
//jobj.memberName = memberName;
//var jsonInfo = JSON.stringify(jobj);
$.ajax({
		type: "POST",
		dataType: "json",
		//async:false,
		url: 'http://ly.iptime.org/safety_news_0713/php/getPost.php',
		//data: jsonInfo,
		data: {memberId:memberName, searchDate:searchDate, page:page, onePage:onePage},
		success: function (data) {
		allPost = data[0]['allPost'];
		
		if(!allPost || allPost==0){
			emptyData = '<li class="news">글이 존재하지 않습니다.</li>';
			$("#main").html(emptyData);			
		}else{
			allPage = Math.ceil(allPost/onePage);//전체 페이지의 수

			if(page<1 || page > allPage){
				alert("존재하지 않는 페이지입니다.");
				history.back();
			}
			
			var oneSection = 10; //한번에보여줄 총 페이지 개수
			var currentSection = Math.ceil(page / oneSection); //현재 섹션
			var allSection = Math.ceil(allPage / oneSection); //전체 섹션의 수

			var firstPage = (currentSection * oneSection) - (oneSection - 1); //현재 섹션의 처음 페이지

			if(currentSection == allSection){
				var lastPage = allPage; //현재 섹션이 마지막 세션 이라면 allPage가 마지막 페이지가 된다.
			}else{
				var lastPage = currentSection * oneSection; //현재 섹션의 마지막 페이지
			}

			var prevPage = ((currentSection - 1) * oneSection); //이전 페이지
			var nextPage = ((currentSection + 1) * oneSection) - (oneSection - 1); //다음 페이지

			var paging = '<ul>';

			//첫 페이지가 아니라면 처음 버튼을 생성
			if(page != 1){
				paging += '<li class="page_start"><a href="./board.html?page=1' + subString + '">처음</a></li>';
			}
			
			//첫 섹션이 아니라면 이전 버튼을 생성
			if(currentSection != 1){
				paging += '<li class="page_start"><a href="./board.html?page=' + prevPage + subString +'">이전</a></li>';
			}
			
			for(var i= firstPage; i<= lastPage; i++){
				if(i == page){
					paging += '<li class="page_current">' + i + '</li>';
				}else{
					paging += '<li class="page"><a href="./board.html?page=' + i + subString + '">' + i + '</a></li>';
				}
			}
			
			//마지막 섹션 아니라면 다음 버튼을 생성
			if(currentSection != allSection){
				paging += '<li class="page_next"><a href="./board.html?page=' + nextPage + subString + '">다음</a></li>';
			}
			
			//마지막 페이지가 아니라면 끝 버튼을 생성
			if(page != allPage){
				paging += '<li class="page_end"><a href="./board.html?page=' + allPage + subString + '">끝</a></li>';
			}
			paging += '</ul>';
			var news = '<ul id=gallery>';
			for(var i=1; i<data.length; i++){
				news += '<li class="news"><a href="./work_confirm.html?bno=';
				news += data[i]['serialNumber'];
				news +='"><img src="http://ly.iptime.org/photo/Aegis_1483421009387.jpg" class="news_thumimg"></a>';
				news += '<img src="images/red.png" class="indigator">';
				news += '<br><br>구역: ';
				news += data[i]['workLocation'];
				news += '<br>';
				news += data[i]['detectedTime'];
				news += '</li>';
				//alert(data.length);
			}
			news += '</ul>';
			//alert("dd");
			$("#main").html(news);
			$("#paging").html(paging);
		}
		
	},
	error: function (request,status,error) {
		console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	}
});
















