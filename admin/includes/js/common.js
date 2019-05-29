// Обработчик AJAX для редактирования отдельного тега
/*
function loadTag(id, tag, tagCONTENT, tagDIV) {
	var ver = navigator.appName;
	var potom = document.getElementById('potomki').value;
	var div = document.getElementById(id);
	if (potom == 'da')
	{
		div.innerHTML = '';
		div.title = 0;
	}
	loadHTML('index.php?from=ajax&mode=metatags&id='+id+'&tag='+tag+'&tagCONTENT='+tagCONTENT+'&browser='+ver+'&potomki='+potom, tagDIV);
}
*/
/*
function checkForm() {
	// сторока ошибок
	var strerr = "Обязательные поля для заполнения, помеченые(*)\n";
	var success = true;
	var forma = document.peopleForm;
	if (forma.title.value==""){
		strerr += "Введите пмя левого меню:\n";
		success = false;
	}
	else
	if (forma.title.value.length<3 || forma.title.value.length>10){
		strerr += "пмя должно быть не короче 3-х и не длинее 10-ти симоволов\n";
		success = false;
	}
	if (forma.name.value==""){
		strerr += "Введите пмя и Фамилию\n";
		success = false;
	}
	if (forma.link.value==""){
		strerr += "Введите Линк\n";
		success = false;
	}
	else
	if (!(/^([a-z0-9_])*[a-z0-9_]$/i).test(forma.link.value)){
		err += "Вы ввели некорректный link, проверьте, пожалуйста, его написание на английском языке\n";
		success = false;
	}
	if (!success) {
		alert(strerr);
		return false;
	}
	else
	return success;
}
*/
/************************** MENU ***********************************/
function accordCat(table, id, action) {
	$.get('ajax.php?mode=menu&table='+table+'&AJAXaction=change&id='+id+'&status='+action, function(data){
	  		$('#id'+id).html(data);
	});
	$.get('ajax.php?mode=menu&table='+table+'&AJAXaction='+action+'&id='+id, function(data){
	  		$('#idsub'+id).html(data);
   	});
	/*
   	loadHTML('ajax.php?mode=menu&table='+table+'&AJAXaction=change&id='+id+'&status='+action, 'id'+id);
	//loadHTML('ajax.php?AJAXaction='+action+'&id='+id, '_idsub'+id);
	var param1 = 'ajax.php?mode=menu&table='+table+'&AJAXaction='+action+'&id='+id;
	var param2 = 'idsub'+id;
	setTimeout('loadHTML("'+param1+'", "'+param2+'")', 1000);
	*/
}
/*
function del(mode, table, id){
	if (confirm('Удалить элемент?'))
		loadHTML('ajax.php?mode='+mode+'&table='+table+'&AJAXaction=delete&id='+id, '_id'+id);
}
*/
function changeAllowed(mode, table, id, visible){
	$.get('ajax.php?mode='+mode+'&table='+table+'&AJAXaction=active&id='+id+'&visible='+visible, function(data){
			$('#idallow'+id).html(data);
		});
}

function changeTableInMenu(table, select){
	var value = select.value;
	$.get('ajax.php?mode=menu&table='+table+'&AJAXaction=changeTable&value='+value, function(data){
			$('#idChangeTable').html(data);
		});
}

/*************************** RAZDEL ************************************/
/*
function changeTableInRazdel(table, select, id){
	var value = select.value;
	loadHTML('ajax.php?mode=razdel&table='+table+'&AJAXaction=changeTable&value='+value, id);
}
*/
function delStructItem(table, id){
	if (confirm('Удалить поле?'))
		$.get('ajax.php?mode=razdel&table='+table+'&AJAXaction=deleteStructItem&id='+id, function(data){
			$('#structItem'+id).html(data);
		});
}

var i = 1;
function addStruct(last_id){
	last_id += i;

	$.get('ajax.php?mode=razdel&AJAXaction=createSelect', function(data) {
		t = $('#tableStruct');
		tr = $('<tr></tr>');
		td1 = $('<td><input type="text" name="name_ucms['+last_id+']" style="width:140px;"></td>');
		td2 = $('<td><input type="text" name="name['+last_id+']" style="width:140px;"></td>');
		td3 = $('<td><select id="selectAddStruct'+i+'" name="ftype['+last_id+']" style="width:140px;">'+data+'</select></td>');
		td4 = $('<td><input type="text" name="properties['+last_id+']" style="width:140px;"></td>');

		$(t).append(tr).find('tr:last').append(td1).append(td2).append(td3).append(td4);

  	});
	i++;
}

var j = 1;
function addSched(last_id)
{
	last_id += j;
	t = $('#film_schedule');
	tr = $('<tr id="schedTR'+last_id+'"></tr>');
	td1 = $('<td>Дата: <div class="right inp"><input style="width: 120px!important;" id="buttonPicker'+last_id+'" type="text" name="film_date['+last_id+']" style="width:100px;"></div></td>');
	td2 = $('<td>Время: <input id="begintime" type="time" name="film_time['+last_id+']" style="width:100px;"></td>');
	td3 = $('<td>Стоимость: <input type="text" name="film_cost['+last_id+']" style="width:100px;"></td>');
	td4 = $('<td>Отображать: <input name="film_sched_visible['+last_id+']" type="radio" style="float:none;" value="1" checked="">Да <input name="film_sched_visible['+last_id+']" type="radio" style="float:none;" value="0">Нет</td>');
	td5 = $('<td><img border="0" src="img/delete.gif" onclick="delSched('+last_id+')"></td>');
	$(t).append(tr).find('tr:last').append(td1).append(td2).append(td3).append(td4).append(td5);
	var datepick_id = 'buttonPicker'+last_id;
	ini_datepick(datepick_id);
	$('#film_sched_count').val(last_id);
	j++;
}

function DelFile(table, id, fname){
	if (confirm('Удалить файл?')){
		$.post('ajax.php?mode=razdel&AJAXaction=deleteFile', {table : table, id : id, fname : fname}, function(data){
			$('#imagetd_'+fname).html(data);
		});
	}
}

function ini_datepick(datepick_id)
{
        $('Datepicker').text('Re-attach');
        $('#'+datepick_id).datepick({dateFormat: 'yyyy-mm-dd', showOnFocus: false,
	    	showTrigger: '<button style="left: 85px!important" type="button" class="trigger"><img width="16" height="16" border="0" alt="" src="img/calendar.gif"></button>'});
}

function delSched(id)
{
	$('#schedTR'+id).hide(500);
	$("#schedTR"+id+" input[name*='todelete']").val(1);
	setTimeout(function(){$("#schedTR"+id+" input:visible").val('');}, 500);
}

/*====send to tickets.od.ua========*/

function tickets_send(json){
  console.log(json);
  /*let`s rock :)*/
  window.location = 'http://dev.tickets.od.ua/admin/tickets/createMap?'+json.toString();
}

/*===============================**/

