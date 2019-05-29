function popimg(url, title, width, height)
{
	str="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\"><html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">\n<title>"+title+"</title>\n</head>\n<body topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>\n<img src='"+url+"' alt='"+title+"'>\n</body>\n</html>";
	f_win = window.open(url ,"BigImage" ,'width='+width+',height='+height+',top='+((screen.height/2)-(height/2))+',left='+((screen.width/2)-(width/2))+',toolbar=no,scrollbars=no,resizable=yes,menubar=no,status=no,directories=no,location=no');
	f_win.document.writeln(str);
	f_win.focus();
	f_win.document.close();
}

function popup(url, title, width, height)
{
	f_win = window.open(url ,"" ,'width='+width+',height='+height+',top='+((screen.height/2)-(height/2))+',left='+((screen.width/2)-(width/2))+',toolbar=no,scrollbars=yes,resizable=yes,menubar=no,status=no,directories=no,location=no');
	f_win.focus();
}

var w3c = (document.getElementById) ?true:false;
var iex = (document.all)            ?true:false;
var ns4 = (document.layers)         ?true:false;
var supported = (w3c || iex || ns4) ?true:false;

var active = false;
var curObj,curNest;

function mousemoved (evt){
	if(iex){
		mousex = window.event.clientX+document.body.scrollLeft;
		mousey = window.event.clientY+document.body.scrollTop;
	}
	else if(ns4){
		mousex = evt.pageX+window.pageXOffset;
		mousey = evt.pageY+window.pageYOffset;
	}
	else{
		mousex = evt.pageX;
		mousey = evt.pageY;
	}
	if(active){
		shiftTo(curObj, mousex+30, mousey-30, curNest);
	}
	return true;
}

// =-=-=-=-=-=-=

function getStyle (objstr, nest){
	nest = (nest) ? "document."+nest+"." : "";
	return (w3c) ? document.getElementById(objstr).style : (iex) ? document.all[objstr].style : (ns4) ? eval(nest+"document."+objstr) : false;
}
function shiftTo (objstr, x, y, nest){
	var obj = getStyle(objstr,nest);
	if(iex){
		obj.pixelLeft = x;
		obj.pixelTop = y-20;
	}
	else if(ns4){
		obj.moveTo(x,y);
	}
	else if(w3c){
		obj.left = x;
		obj.top = y-20;
	}
}
function show (objstr,nest){
	curObj = objstr;
	curNest = (nest) ? nest : null;
	getStyle(objstr,nest).visibility = "visible";
	active = true;
}
function hide (){
	getStyle(curObj,curNest).visibility = "hidden";
	active = false;
}

// =-=-=-=-=-=-=

if(supported){
	if(ns4){
		document.captureEvents(Event.MOUSEMOVE);
	}
	document.onmousemove = mousemoved;
}

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
function est(d){
	document.getElementById(d).style.visibility="visible";
	}
function net(d){
	document.getElementById(d).style.visibility="hidden";
	}
function show (objstr,nest){
	curObj = objstr;
	curNest = (nest) ? nest : null;
	getStyle(objstr,nest).visibility = "visible";
	active = true;
}
function hide (){
	getStyle(curObj,curNest).visibility = "hidden";
	active = false;
}
