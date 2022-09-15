document.getElementById('StandBtn').onclick = function() {
	var url = document.getElementById('Standard_plan_name').innerHTML;
	url = url.replace(/'/g,'');
	url = url.replace(/amp;/g,"");
	if(url.search("xecurify")==-1)
		window.location = url;
    else
    	window.open(url,"_blank" );
}

document.getElementById('premium_btn').onclick = function() {
	var url = document.getElementById('premium_plan_name').innerHTML;
	url = url.replace(/'/g,'');
	url = url.replace(/amp;/g,"");

	if(url.search("xecurify")==-1)
		window.location = url;
    else
    	window.open(url,"_blank" );
}

document.getElementById('enterprise_btn').onclick = function() {
	var url = document.getElementById('enterprise_plan_name').innerHTML;
	url = url.replace(/'/g,'');
	url = url.replace(/amp;/g,'');
	if(url.search("xecurify")==-1)
		window.location = url;
    else
    	window.open(url,"_blank" );
}