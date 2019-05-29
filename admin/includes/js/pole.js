_editor_url = "files/";
goodTags0=["a","p","br","strong","b","em","i","tt","code","pre","ul","ol","li","img","table","thead","tfoot","caption","tr","td","th","col","colgroup","h1","h2","h3","h4","h5","h6","small","big","sub","sup","div"]

canEmptyTags0=["td","th"]

goodAttributes0=[]
goodAttributes0[""] = ["href", "target", "name", "title", "alt", "src", "id", "bgColor", "color"]

goodAttributes0["img"] = ["width", "height", "border", "align"]
goodAttributes0["table"] = ["cellSpacing", "cellPadding", "border", "width %"]
goodAttributes0["td"] = ["colSpan", "rowSpan"]
goodAttributes0["tr td col colgroup"] = ["noWrap", "align", "vAlign", "width %"]
goodAttributes0["a area"] = ["href", "name"]
goodAttributes0["br"] = ["clear"]

mustDieAttributes=["x:str","x:num","x:fmla"]

goodClasses0=["h1","h2","h3","h4","h5","h6","none"]

goodTags=[]
for(i in goodTags0){
	goodTags[goodTags0[i]]=true
}
canEmptyTags=[]
for(i in canEmptyTags0){
	canEmptyTags[canEmptyTags0[i]]=true
}

goodAttributes=[]
for(i in goodAttributes0){
	var splitted=i.split(" ")
	for(ii in splitted){
		if(!goodAttributes[splitted[ii]]) goodAttributes[splitted[ii]]=[]
		for(j in goodAttributes0[i]){
			goodAttributes[splitted[ii]][goodAttributes0[i][j]]=true
		}
	}
}

goodClasses=[]
for(i in goodClasses0){
	goodClasses[goodClasses0[i]]=true
}

function cleanObject(obj)
{
	var s="";
	var myAttributes=[];
	var i;
	if(obj.outerHTML.substr(0,2)=="<?"){
		obj.removeNode(false);
		return;
	}
	var tag=obj.tagName.toLowerCase()
	if(!goodTags[tag]  || (obj.canHaveHTML && obj.innerHTML=="" && !canEmptyTags[tag])){
		try{
			obj.removeNode(false);
		}catch(e){}

		return;
	}
	var a=obj.attributes;
	if(!a)
		return;
	for(i in a){
		if(""+a[i]!="null"){
			myAttributes[i]=a[i];
		}
	}

	for(i in myAttributes){
		if(
			(
				!goodAttributes[""][i] &&
				(
					!goodAttributes[tag] || !goodAttributes[tag][i] || i=="class" || i=="className"
				)
			)
		){

			if(i=="class"){
				i="className"
			}
			if(i!="className" || !goodClasses[obj.className]){
				obj.removeAttribute(i)
			}
		}
	}
	if(obj.tagName.toLowerCase()!="table"){
		for(i in mustDieAttributes){
			obj.removeAttribute(mustDieAttributes[i]);
		}
	}
	obj.style.cssText=""
}

function cleanTree(o,mustClean)
{
	var c=o.children
	var i
	if(c){
		for(i=c.length-1;i>=0;i--){
			cleanTree(c[i],true)
		}
	}
	if(mustClean) cleanObject(o)
}

document.write('<style type="text/css">\n');
document.write('.btn     { width: 22px; height: 22px; border: 1px solid #E5E5E5; margin: 0; padding: 0;background-color:#E5E5E5;background-image: none; }\n');
document.write('.btnOver { width: 22px; height: 22px; border: 1px outset;background-image: none; }\n');
document.write('.btnDown { width: 22px; height: 22px; border: 1px inset; background-color: #EFEFEF; }\n');
document.write('.btnNA   { width: 22px; height: 22px; border: 1px solid buttonface; filter: alpha(opacity=25); }\n');
document.write('</style>\n');

function editor_defaultConfig(objname) {

this.width =  "auto";
this.height = "auto";
this.bodyStyle = 'background-color: #FFFFFF';
this.imgURL = 'images/';
this.debug  = 0;

this.replaceNextlines = 0;
this.plaintextInput = 0;
this.toolbar = [
	['styles','separator'],
	['bold','italic','underline','strikethrough','subscript','superscript','separator'],
	['caps','uncaps','separator'],
	['justifyleft','justifycenter','justifyright','justifyfull','Outdent','Indent','separator'],
	['OrderedList','UnOrderedList','separator'],
	['forecolor','backcolor','cleanhtml','separator'],
	//['linebreak'],
	['HorizontalRule','Createlink','InsertImage','specialchars'],
	['htmlmode'],
];
this.stylesheet = ["/includes/css/main.css"];
this.fontstyles = [];

this.btnList = {
    "bold":           ['Bold',                 'Жирный',                'editor_action(this.id)',		'ed_format_bold.gif'],
    "caps":           ['caps',                 'Верхний регистр',		'editor_action(this.id)',		'ed_format_caps.gif'],
    "uncaps":         ['uncaps',               'Нижний регистр',		'editor_action(this.id)',		'ed_format_uncaps.gif'],
    "italic":         ['Italic',               'Курсив',                 'editor_action(this.id)',		'ed_format_italic.gif'],
    "underline":      ['Underline',            'Подчеркнутый',              'editor_action(this.id)', 		'ed_format_underline.gif'],
    "strikethrough":  ['StrikeThrough',        'Перечеркнутый',             'editor_action(this.id)', 		'ed_format_strike.gif'],
    "subscript":      ['SubScript',            'Нижний индекс',             'editor_action(this.id)',  		'ed_format_sub.gif'],
    "superscript":    ['SuperScript',          'Верхний индекс',            'editor_action(this.id)',  		'ed_format_sup.gif'],
    "justifyleft":    ['JustifyLeft',          'Выровнять по левому краю',  'editor_action(this.id)',  		'ed_align_left.gif'],
    "justifycenter":  ['JustifyCenter',        'Выровнять по центру',       'editor_action(this.id)',  		'ed_align_center.gif'],
    "justifyright":   ['JustifyRight',         'Выровнять по правому краю', 'editor_action(this.id)',  		'ed_align_right.gif'],
    "justifyfull":    ['JustifyFull',          'Выровнять равномерно по ширине',      'editor_action(this.id)',  		'ed_align_justify.gif'],
    "orderedlist":    ['InsertOrderedList',    'Нумерованый список',        'editor_action(this.id)',  		'ed_list_num.gif'],
    "unorderedlist":  ['InsertUnorderedList',  'Ненумерованый список',      'editor_action(this.id)',  		'ed_list_bullet.gif'],
    "outdent":        ['Outdent',              'Уменьшить отступ',          'editor_action(this.id)',		'ed_indent_less.gif'],
    "indent":         ['Indent',               'Увеличить отступ',          'editor_action(this.id)',		'ed_indent_more.gif'],
    "forecolor":      ['ForeColor',            'Цвет текста',               'editor_action(this.id)',		'ed_color_fg.gif'],
    "backcolor":      ['BackColor',            'Цвет фона',                 'editor_action(this.id)',		'ed_color_bg.gif'],
    "specialchars":   ['SpecialChars',         'Спец символы',       'editor_action(this.id)',		'ed_charmap.gif'],
    "horizontalrule": ['InsertHorizontalRule', 'Горизонтальный разделитель','editor_action(this.id)',		'ed_hr.gif'],
    "createlink":     ['CreateLink',           'Вставка ссылки',           'editor_action(this.id)',		'ed_link.gif'],
    "insertimage":    ['InsertImage',          'Вставка изображения',      'editor_action(this.id)',		'ed_image.gif'],
    "htmlmode":       ['HtmlMode',             'Просмотр HTML-кода',        'editor_setmode(\''+objname+'\')', 'ed_html.gif'],
    "vrlinks":        ['vrlinks',              'Сделать ссылки непрямыми',  'editor_action(this.id)',		'ed_link.gif'],
    "cleanhtml":      ['cleanhtml',            'Оптимизировать код (после Exel, Word)',       'editor_action(this.id)',  'ed_word_cleaner.gif'],
    "help":           ['showhelp',             'Help using editor',  'editor_action(this.id)',  'ed_help.gif']};
}

function test(objname,btnCmdID)
{
	var editor_obj = document.all["_" +objname + "_editor"];
	var editdoc = editor_obj.contentWindow.document;
	return editdoc.queryCommandState(btnCmdID);
}

function editor_generate(objname) {
  var config = new editor_defaultConfig(objname);
  var obj    = document.all[objname];
  var w;
  obj.config = config;
  if (!config.width || config.width == "auto")
  {
  	if(obj.style.width)
  	{
  		w=config.width = obj.style.width;
  	}else if (obj.cols)
  	{
  		w=config.width = (obj.cols * 5) + 22;
  	}else
  	{
  		w=config.width = '100%';
  	}
  }
  if (!config.height || config.height == "auto")
  {
  	if (obj.style.height)
  	{
  		config.height = obj.style.height;
  	}else if (obj.rows)
  	{
  		config.height = obj.rows * 17;
  	}else
  	{
  		config.height = '100%';
  	}
  }

  var tblOpen  = '<table border=0 cellspacing=0 cellpadding=0 style="float: left;"  unselectable="on"><tr><td style="border: none; padding: 1 0 0 0"><nobr>';
  var tblClose = '</nobr></td></tr></table>\n';

  var toolbar = '';
  //toolbar+='<select style="width:45px"></select>';
  var btnGroup, btnItem, aboutEditor;
  for (var btnGroup in config.toolbar)
  {
  	if (config.toolbar[btnGroup].length == 1&&config.toolbar[btnGroup][0].toLowerCase() == "linebreak")
  	{
  		//toolbar += '<br clear="all" />';
  		toolbar += '</td></tr><tr><td>';
    	continue;
    }
    //

    toolbar += tblOpen;
    
    for (var btnItem in config.toolbar[btnGroup])
    {
    	var btnName = config.toolbar[btnGroup][btnItem].toLowerCase();
    	if (btnName == "separator")
    	{
        	toolbar += '<span style="border: 1px inset; width: 1px; font-size: 16px; height: 16px; margin: 0 3 0 3"></span>';
        	continue;
      	}else if(btnName == "styles")
      	{
      		toolbar+='<select style="width:45px" id="estyles" onchange="editor_action(';
			toolbar+="'_"+objname+"_styles'";
			toolbar+=');">';
      		toolbar+='<option value="">';
      		for(var st in goodClasses0)
      		{
      			toolbar+='<option value="'+goodClasses0[st]+'">'+goodClasses0[st];
      		}
      		toolbar+='</select>';
      	}
      	else
      	{
      		var btnObj = config.btnList[btnName];
            var btnCmdID   = btnObj[0];
      		var btnTitle   = btnObj[1];
      		var btnOnClick = btnObj[2];
      		var btnImage   = btnObj[3];
      		toolbar += '<button title="' +btnTitle+ '" id="_' +objname+ '_' +btnCmdID+ '" class="btn" onClick="' +btnOnClick+ '" '
      		+ ' onmouseover="if(this.className==\'btn\'){this.className=\'btnOver\'}" onmouseout="if(this.className==\'btnOver\'){this.className=\'btn\'}" unselectable="on"><img src="' +config.imgURL + btnImage+ '" border=0 unselectable="on"></button>';
      	    //onmousedown="if(test(\''+objname+'\', \''+btnCmdID+'\')){this.className=\'btn\'}else {this.className=\'btnDown\'}"
      	}
    }
    toolbar += tblClose;
  }
  var editor = '<img width="1" height="4" border="0"><span id="_editor_toolbar">'
  +'<table border="0" bgcolor="#E5E5E5" width="'+w+'" cellspacing="0" cellpadding="0" style="padding: 0 0 0 0" unselectable="on"><tr><td>\n'
  + toolbar
  + '</td></tr></table></span>\n'
  + '<img width="1" height="4" border="0"><br><textarea name="f" ID="_' +objname + '_editor" style="width:'
  +config.width+ '; height:' +config.height+ '; margin-top: -1px; margin-bottom: -1px;" wrap=soft></textarea>';


  if (!config.debug)
  {
  	obj.style.display = "none";
  }

  if (config.plaintextInput)
  {
  	var contents = obj.value;
    contents = contents.replace(/\r\n/g, '<br>');
    contents = contents.replace(/\n/g, '<br>');
    contents = contents.replace(/\r/g, '<br>');
    obj.value = contents;
  }

  obj.insertAdjacentHTML('afterEnd', editor)

  editor_setmode(objname, 'init');

  for(var idx=0; idx < document.forms.length; ++idx)
  {
    var r = document.forms[idx].attachEvent('onsubmit', function() { editor_filterOutput(objname); });
    if (!r) { alert("Error attaching event to form!"); }
  }

	return true;

}

function editor_action(button_id)
{
	var BtnParts = Array();
	BtnParts = button_id.split("_");
	var objname    = button_id.replace(/^_(.*)_[^_]*$/, '$1');
	var cmdID      = BtnParts[ BtnParts.length-1 ];
	if(cmdID!='estyles')
	var button_obj = document.all[button_id];
	var editor_obj = document.all["_" +objname + "_editor"];
	var config     = document.all[objname].config;

	if (editor_obj.tagName.toLowerCase() == 'textarea')
	{
  		return;
	}

	var editdoc = editor_obj.contentWindow.document;
	editor_focus(editor_obj);
	if(cmdID=='styles')
	{
		
		sel=editdoc.selection.createRange();
		text=sel.htmlText;
   		var selelem=document.all['estyles'];
   		var tag=selelem[selelem.selectedIndex].value;
   		if(text!='')
   		{
   			if(tag!='none')
   				editor_insertHTML(objname,'<'+tag+'>'+text+'</'+tag+'>');
   			else
   			{
   				editdoc.execCommand('Delete');
   				for (var i in goodClasses0)
   				{
   					var reg="<[/]*"+goodClasses0[i]+">";
   					var r=new RegExp(reg,"ig");
   					if(goodClasses0[i]!='none')
   						text=text.replace(r,'');
   				}
   				editor_insertHTML(objname,text+' ');
   			}
   		}
   		selelem.selectedIndex=0;
	}else if (cmdID == 'ForeColor' || cmdID == 'BackColor')
	{
		var oldcolor = _dec_to_rgb(editdoc.queryCommandValue(cmdID));
	  	var newcolor = showModalDialog("popups/select_color.html", oldcolor, "dialogHeight: 202px; dialogWidth: 240px; resizable: no; help: no; status: yes; scroll: no;");
	   	if (newcolor != null)
    	{
    		editdoc.execCommand(cmdID, false, "#"+newcolor);
    	}
    	editor_obj.focus();
	}else
	{
	    if (cmdID.toLowerCase() == 'subscript' && editdoc.queryCommandState('superscript'))
	    {
	    	editdoc.execCommand('superscript');
	    }
	    if (cmdID.toLowerCase() == 'superscript' && editdoc.queryCommandState('subscript'))
	    {
	    	editdoc.execCommand('subscript');
	    }
	    if (cmdID.toLowerCase() == 'createlink')
	    {
	    	link=showModalDialog("popups/insert_link.html", window, "dialogHeight: 185px; dialogWidth: 300px; resizable: no; help: no; status: yes; scroll: no; ");
	    	editor_insertHTML(objname,link);
      		editor_obj.focus();
    	}else if (cmdID.toLowerCase() == 'preview')
    	{
			if (viewing[objname])
			{
				editdoc.body.contentEditable = true;
				editor_updateToolbar(objname, 'enable');
				viewing[objname] = false;
			}else
			{
				editdoc.body.contentEditable = false;
				editor_updateToolbar(objname, 'disable');
				viewing[objname] = true;
			}
   		}else if (cmdID.toLowerCase() == 'insertimage')
   		{
			img=showModalDialog("./php/pictures.php", 1, "dialogHeight: 460px; dialogWidth: 625px; resizable: no; help: no; status: yes; scroll: no; ");
			if(img==undefined)
				img='';
			editor_insertHTML(objname,img);
      		editor_obj.focus();
   			
		}else if (cmdID.toLowerCase() == 'specialchars')
		{
      		text=showModalDialog("popups/special_char.html", window, "dialogHeight: 270px; dialogWidth: 345px; resizable: no; help: no; status: yes; scroll: no; ");
      		editor_insertHTML(objname,text);
      		editor_obj.focus();
    	}else if (cmdID.toLowerCase() == 'cleanhtml')
    	{
			cleanTree(editdoc.body, false);
			var res, prev;
			do
			{
				prev = editdoc.body.innerHTML;
				res = editdoc.body.innerHTML.replace("<P>&nbsp;</P>", '');
				if (prev!=res)
					editdoc.body.innerHTML = res;
			}while(prev!=res);
    	}else if (cmdID.toLowerCase() == 'vrlinks')
    	{
			editdoc.body.innerHTML = editdoc.body.innerHTML.replace('href="http://'+location.hostname, 'href="');
			rx = new RegExp('href="(http://[^"]*)"', 'gi');
			editdoc.body.innerHTML = editdoc.body.innerHTML.replace(rx, 'href="" onmousemove="this.href=\'$1\'" onfocus="this.href=\'$1\'"');
   		}else if(cmdID.toLowerCase() == 'caps')
   		{
   			sel=editdoc.selection.createRange();
   			text=sel.text;
   			editor_insertHTML(objname,text.toUpperCase() );
   		}else if(cmdID.toLowerCase() == 'uncaps')
   		{
   			sel=editdoc.selection.createRange();
   			text=sel.text;
   			editor_insertHTML(objname,text.toLowerCase() );
   		}else
    	{
    		editdoc.execCommand(cmdID);
    	}
   }
   editor_event(objname);
}

function editor_event(objname,runDelay) {
  var config = document.all[objname].config;
  var editor_obj  = document.all["_" +objname+  "_editor"];
  if (runDelay == null)
  {
  	runDelay = 0;
  }
  var editdoc;
  var editEvent = editor_obj.contentWindow ? editor_obj.contentWindow.event : event;

    if (editEvent && editEvent.keyCode) {
      var ord       = editEvent.keyCode;
      var ctrlKey   = editEvent.ctrlKey;
      var altKey    = editEvent.altKey;
      var shiftKey  = editEvent.shiftKey;

      if (ord == 16) { return; }
      if (ord == 17) { return; }
      if (ord == 18) { return; }

	   if (ctrlKey && ord == 10 && editEvent.type == 'keypress')
			editEvent.keyCode = 13;
       if (!ctrlKey &&shiftKey&& ord == 13 && editEvent.type == 'keypress' && config.mode == "wysiwyg") {
         editEvent.returnValue = false;
         editor_insertHTML(objname, "<br />");
         return;
       }

      if (ctrlKey && (ord == 122 || ord == 90)) {
        return;
      }
      if ((ctrlKey && (ord == 121 || ord == 89)) ||
          ctrlKey && shiftKey && (ord == 122 || ord == 90)) {
        return;
      }
    }

  if (runDelay > 0) { return setTimeout(function(){ editor_event(objname); }, runDelay); }

  if (this.tooSoon == 1 && runDelay >= 0) { this.queue = 1; return; }
  this.tooSoon = 1;
  setTimeout(function(){
    this.tooSoon = 0;
    if (this.queue) { editor_event(objname,-1); };
    this.queue = 0;
    }, 333);


  editor_updateOutput(objname);
  editor_updateToolbar(objname);

}

function editor_updateToolbar(objname,action)
{
	var config = document.all[objname].config;
	var editor_obj  = document.all["_" +objname+  "_editor"];
	if (action == "enable" || action == "disable")
	{
		var tbItems = new Array('FontName','FontSize','FontStyle');
		for (var btnName in config.btnList)
		{
	   		tbItems.push(config.btnList[btnName][0]);
	  	}
		for (var idxN in tbItems)
		{
			var cmdID = tbItems[idxN].toLowerCase();
			var tbObj = document.all["_" +objname+ "_" +tbItems[idxN]];
			if (cmdID == "htmlmode" || cmdID == "about" || cmdID == "showhelp" || cmdID == "popupeditor" || cmdID == 'preview')
			{
				continue;
			}
			if (tbObj == null)
			{
				continue;
			}
			var isBtn = (tbObj.tagName.toLowerCase() == "button") ? true : false;
			if (action == "enable")
			{
				tbObj.disabled = false;
				if (isBtn)
				{
					tbObj.className = 'btn';
				}
			}
			if (action == "disable")
			{
				tbObj.disabled = true;
				if (isBtn)
				{
					tbObj.className = 'btnNA';
				}
			}
		}
		return;
	}
	if (editor_obj.tagName.toLowerCase() == 'textarea')
	{
		return;
	}
	var editdoc = editor_obj.contentWindow.document;
	if (editdoc.body.contentEditable)
		return;


}

function editor_updateOutput(objname)
{
	var config     = document.all[objname].config;
	var editor_obj  = document.all["_" +objname+  "_editor"];
	var editEvent = editor_obj.contentWindow ? editor_obj.contentWindow.event : event;
	var isTextarea = (editor_obj.tagName.toLowerCase() == 'textarea');
	var editdoc = isTextarea ? null : editor_obj.contentWindow.document;
	var contents;
	if (isTextarea)
	{
		contents = editor_obj.value;
	}else
	{
		contents = editdoc.body.innerHTML;
	}
	if (config.lastUpdateOutput && config.lastUpdateOutput == contents)
	{
		return;
	}else
	{
		config.lastUpdateOutput = contents;
	}
	document.all[objname].value = contents;
}

function editor_filterOutput(objname)
{
  editor_updateOutput(objname);
  var contents = document.all[objname].value;
  var config   = document.all[objname].config;
  if (contents.toLowerCase() == '<p>&nbsp;</p>')
  {
  	contents = "";
  }
  var filterTag = function(tagBody,tagName,tagAttr)
  {
    tagName = tagName.toLowerCase();
    var closingTag = (tagBody.match(/^<\//)) ? true : false;
    if (tagName == 'img')
    {
    	tagBody = tagBody.replace(/(src\s*=\s*.)[^*]*(\*\*\*)/, "$1$2");
    }
    if (tagName == 'a')
    {
    	tagBody = tagBody.replace(/(href\s*=\s*.)[^*]*(\*\*\*)/, "$1$2");
    }
    if(tagName == 'b' || tagName == 'strong')
    {
      if (closingTag)
      {
      	tagBody = "</b>";
      } else
      {
      	tagBody = "<b>";
      }
    }else if (tagName == 'i' || tagName == 'em')
    {
      if (closingTag)
      {
      	tagBody = "</i>";
      }else
      {
      	tagBody = "<i>";
      }
    }

    return tagBody;
  };
  RegExp.lastIndex = 0;
  var matchTag = /<\/?(\w+)((?:[^'">]*|'[^']*'|"[^"]*")*)>/g;
  contents = contents.replace(matchTag, filterTag);
  if (config.replaceNextlines)
  {
    contents = contents.replace(/\r\n/g, ' ');
    contents = contents.replace(/\n/g, ' ');
    contents = contents.replace(/\r/g, ' ');
  }
  document.all[objname].value = contents;
}

function editor_setmode(objname, mode) {
  var config     = document.all[objname].config;
  var editor_obj = document.all["_" +objname + "_editor"];
	if (document.readyState != 'complete')
	{
		setTimeout(function() { editor_setmode(objname,mode) }, 25);
		return;
	}

	var TextEdit   = '<textarea name="f" ID="_' +objname + '_editor" style="width:' +editor_obj.style.width+ '; height:' +editor_obj.style.height+ '; margin-top: -1px; margin-bottom: -1px;"></textarea>';
	var RichEdit   = '<table cellspacing="' + (mode=='init'?'1':'0') + '" cellpadding="0" border="0" bgcolor="#b6b6b6"><tr><td bgcolor="b6b6b6"><iframe frameborder="0" ID="_' +objname+ '_editor"    style="width:'+editor_obj.style.width+ '; height:' +editor_obj.style.height+ ';"></iframe></td></tr></table>';

	if (mode == "textedit" || editor_obj.tagName.toLowerCase() == 'iframe')
	{
		config.mode = "textedit";
		var editdoc = editor_obj.contentWindow.document;
		var contents = editdoc.body.createTextRange().htmlText;
		editor_obj.outerHTML = TextEdit;
		editor_obj = document.all["_" +objname + "_editor"];
		editor_obj.value = contents;
		editor_event(objname);

		editor_updateToolbar(objname, "disable");

		editor_obj.onkeydown   = function() { editor_event(objname); }
		editor_obj.onkeypress  = function() { editor_event(objname); }
		editor_obj.onkeyup     = function() { editor_event(objname); }
		editor_obj.onmouseup   = function() { editor_event(objname); }
		editor_obj.ondrop      = function() { editor_event(objname, 100); }
		editor_obj.oncut       = function() { editor_event(objname, 100); }
		editor_obj.onpaste     = function() { editor_event(objname, 100); }
		editor_obj.onblur      = function() { editor_event(objname, -1); }

		editor_updateOutput(objname);
		editor_focus(editor_obj);
	}else
	{
		config.mode = "wysiwyg";
		var contents = editor_obj.value;
		if (mode == 'init')
		{
			contents = document.all[objname].value;
		}

		editor_obj.outerHTML = RichEdit;
		editor_obj = document.all["_" +objname + "_editor"];

		var html = "";
		html += '<html><head>\n';
		for (var i=0; i<config.stylesheet.length; i++)
			if (config.stylesheet[i])
			{
				html += '<link href="' +config.stylesheet[i]+ '" rel="stylesheet" type="text/css">\n';
				html += '<script language="JavaScript" src="files/scripts.js" type="text/javascript"></script>\n';
			}
		html += '<style>\n';
		html += 'body {' +config.bodyStyle+ '} \n';
		for (var i in config.fontstyles)
		{
			var fontstyle = config.fontstyles[i];
			if (fontstyle.classStyle)
			{
				html += '.' +fontstyle.className+ ' {' +fontstyle.classStyle+ '}\n';
			}
		}
		html += '</style>\n'
		+ '</head>\n'
		+ '<body contenteditable="true" topmargin=1 leftmargin=1'
		+'>'
		+ contents
		+ '</body>\n'
		+ '</html>\n';

		var editdoc = editor_obj.contentWindow.document;

		editdoc.open();
		editdoc.write(html);
		editdoc.close();

		editor_updateToolbar(objname, "enable");

		editdoc.objname = objname;

		editdoc.onkeydown      = function() { editor_event(objname); }
		editdoc.onkeypress     = function() { editor_event(objname); }
		editdoc.onkeyup        = function() { editor_event(objname); }
		editdoc.onmouseup      = function() { editor_event(objname); }
		editdoc.body.ondrop    = function() { editor_event(objname, 100); }
		editdoc.body.oncut     = function() { editor_event(objname, 100); }
		editdoc.body.onpaste   = function() { editor_event(objname, 100); }
		editdoc.body.onblur    = function() { editor_event(objname, -1); }

		if (mode != 'init')
		{
			editor_focus(editor_obj);
		}
	}

	if (mode != 'init')
	{
		editor_event(objname);
	}
}

function editor_focus(editor_obj) {

  if (editor_obj.tagName.toLowerCase() == 'textarea') {
    var myfunc = function() { editor_obj.focus(); };
    setTimeout(myfunc,100);
  }

  else {
    var editdoc = editor_obj.contentWindow.document;
    var editorRange = editdoc.body.createTextRange();
    var curRange    = editdoc.selection.createRange();

    if (curRange.length == null &&
        !editorRange.inRange(curRange)) {
      editorRange.collapse();
      editorRange.select();
      curRange = editorRange;
    }
  }

}

function _dec_to_rgb(value) {
  var hex_string = "";
  for (var hexpair = 0; hexpair < 3; hexpair++) {
    var myByte = value & 0xFF;
    value >>= 8;
    var nybble2 = myByte & 0x0F;
    var nybble1 = (myByte >> 4) & 0x0F;
    hex_string += nybble1.toString(16);
    hex_string += nybble2.toString(16);
  }
  return hex_string.toUpperCase();
}

function editor_insertHTML(objname, str1,str2, reqSel) {
  var config     = document.all[objname].config;
  var editor_obj = document.all["_" +objname + "_editor"];
  if (str1 == null) { str1 = ''; }
  if (str2 == null) { str2 = ''; }

  if (document.all[objname] && editor_obj == null) {
    document.all[objname].focus();
    document.all[objname].value = document.all[objname].value + str1 + str2;
    return;
  }

  if (editor_obj == null) { return alert("Unable to insert HTML.  Invalid object name '" +objname+ "'."); }

  editor_focus(editor_obj);

  var tagname = editor_obj.tagName.toLowerCase();
  var sRange;

  if (tagname == 'iframe') {
    var editdoc = editor_obj.contentWindow.document;
    sRange  = editdoc.selection.createRange();
    var sHtml   = sRange.htmlText;

    if (sRange.length) { return alert("Unable to insert HTML.  Try highlighting content instead of selecting it."); }

    var oldHandler = window.onerror;
    window.onerror = function() { alert("Unable to insert HTML for current selection."); return true; }
    if (sHtml.length) {
      if (str2) { sRange.pasteHTML(str1 +sHtml+ str2) }
      else      { sRange.pasteHTML(str1); }
    } else {
      if (reqSel) { return alert("Unable to insert HTML.  You must select something first."); }
      sRange.pasteHTML(str1 + str2);
    }
    window.onerror = oldHandler;
  }

  else if (tagname == 'textarea') {
    editor_obj.focus();
    sRange  = document.selection.createRange();
    var sText   = sRange.text;

    if (sText.length) {
      if (str2) { sRange.text = str1 +sText+ str2; }
      else      { sRange.text = str1; }
    } else {
      if (reqSel) { return alert("Unable to insert HTML.  You must select something first."); }
      sRange.text = str1 + str2;
    }
  }
  else { alert("Unable to insert HTML.  Unknown object tag type '" +tagname+ "'."); }

  sRange.collapse(false);
  sRange.select();

}

function editor_getHTML(objname) {
  var editor_obj = document.all["_" +objname + "_editor"];
  var isTextarea = (editor_obj.tagName.toLowerCase() == 'textarea');

  if (isTextarea) { return editor_obj.value; }
  else            { return editor_obj.contentWindow.document.body.innerHTML; }
}

function editor_setHTML(objname, html) {
  var editor_obj = document.all["_" +objname + "_editor"];
  var isTextarea = (editor_obj.tagName.toLowerCase() == 'textarea');

  if (isTextarea) { editor_obj.value = html; }
  else            { editor_obj.contentWindow.document.body.innerHTML = html; }
}

function editor_appendHTML(objname, html) {
  var editor_obj = document.all["_" +objname + "_editor"];
  var isTextarea = (editor_obj.tagName.toLowerCase() == 'textarea');

  if (isTextarea) { editor_obj.value += html; }
  else            { editor_obj.contentWindow.document.body.innerHTML += html; }
}