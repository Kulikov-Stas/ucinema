function image(url,title,width, height)
{
	if(height>screen.height-200)
		height=screen.height-200;
	if(width>screen.width-60)
		width=screen.width-60;
	str="<html><head><title>"+title+"</title></head><body style='margin:0;'><div style='overflow:auto; width:100%; height:100%;'><img src='"+url+"' alt='"+title+"'></div></body></html>";
	w=window.open(url,'',"width="+width+",height="+height+",top="+(screen.height/2-height/2-30)+",left="+(screen.width/2-width/2)+",resizable=1,menubar=0,status=1,toolbar=0,directories=0,location=0");
	w.document.writeln(str);
	w.document.close();
	w.focus();
}
function link(path,n, design)
{
	if(document.forms['pagesform']!='undefined')
	{
		document.forms['pagesform'].elements['s'].value=n;
		document.forms['pagesform'].elements['design'].value=design;
		document.forms['pagesform'].action=path;
		document.forms['pagesform'].submit();
	}
}
