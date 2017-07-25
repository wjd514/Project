
var memberId = "asdf";
var member = new Array();




$(document).ready(function() {
	
	//var member = "";
	$.ajax({
		type: "GET",
		dataType: "json",
		url: 'http://ly.iptime.org/dd/php/memberlist.php',
		data: {memberId:memberId},
		success: function (data) {
			//alert(data[0].phoneNumber);
			var memberInfo ='<ol class="memberInfo">';
			for(var i=0; i<data.length; i++){
				member[i] = data[i];
				memberInfo += '<a href="javascript:void(0);" onclick="javascript:sendChildValue(';
				memberInfo += i;
				memberInfo += ')">';
				memberInfo += '<li>'+data[i].id+'</li>';
				memberInfo += '<li>'+data[i].name+'</li>';
				memberInfo += '<li>'+data[i].position+'</li>';
				memberInfo += '<li>'+data[i].section+'</li>';
				memberInfo += '</a>';

				//memberInfo = memberInfo.replace(/#{data[i]['name']}/gi,data[i]['name']);
			}
			memberInfo += '</ol>';

			$("#memberlist").html(memberInfo);

		},

		error: function (request,status,error) {
			console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
	
});

function sendChildValue(name){
	//alert(member[name].id);
	window.opener.setChildValue(member[name]);
	window.close();
}