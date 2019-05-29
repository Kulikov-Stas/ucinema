var request;
var dest;
function inter(){
	setTimeout('loadHTML(URL, destination, params)', 3000)
}

function loadHTML(URL, destination, params) {
	//params = "param=1&name=binny";
	dest = destination;
	if (window.XMLHttpRequest) {
		request = new XMLHttpRequest();
		request.onreadystatechange = processStateChange;
		if (params == null) {
			request.open('GET', URL, true);
			request.send(null);
			
		}
		else {
			request.open('POST', URL, false);
			//Send the proper header information along with the request
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.setRequestHeader("Content-length", params.length);
			request.setRequestHeader("Connection", "close");
			request.send(params);
		}
	}
	else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
		if (request) {
			request.onreadystatechange = processStateChange;
			if (params == null) {
				request.open('GET', URL, true);
				request.send();
			}
			else {
				request.open('POST', URL, false);
				//Send the proper header information along with the request
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.setRequestHeader("Content-length", params.length);
				request.setRequestHeader("Connection", "close");
				request.send(params);
			}
		}
	}
}

/**
* 0 (Неинициализирован)
* 1 (Инициализирован)
* 2 (Отправлен)
* 3 (Загружается)
* 4 (Загружен)
*/
function processStateChange() {
	/*
	if (request.readyState == 1){
		contentDiv. = '<img src="buttons/loading.gif">';
	}
	*/
	if (request.readyState == 4) {
		contentDiv = document.getElementById(dest);
		if (request.status == 200) {
			response = request.responseText;
			contentDiv.innerHTML = response;
			evalScript(response);
		}
		else {
			contentDiv.innerHTML = '<center>Error: Status '+request.status+'</center>';
		}
	}
}

function evalScript(scripts){	
	try{	
		if(scripts != ''){	
			var script = "";
			scripts = scripts.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, function(){
				if (scripts !== null) script += arguments[1] + '\n';
				return '';
			});
			
			if(script) (window.execScript) ? window.execScript(script) : window.setTimeout(script, 0);
		}
		return false;
	}
	catch(e){	
		alert(e)
	}
}