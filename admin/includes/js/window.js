function openWindow(target, width, height, left, top )
{
	str="width="+width+", height="+height+", left="+left+", top="+top+", resizable=no,status=1";
  	w1=window.open(target, "", str);
}
function setactfield(actfield)
{
	if(actfield=='del')
	{
		document.forms['form2'].actfield.value=actfield;
		document.forms['form2'].submit();
	}
}
function delField(field, flag)
{
	if(flag==0)
	{
		document.forms['mainpart'].actfield.value='delfield';
		document.forms['mainpart'].extra.value=field;
		document.forms['mainpart'].submit();
	}
}
function delTable()
{
	openWindow("./php/deltable.php",614,100,300,300);
}
function delOrCopy(elem)
{
	if(elem.value=='massdel')
	{
		openWindow("./php/delfield.php", 614,100,300,300);
	}
}
