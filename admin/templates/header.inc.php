<?php
  function dateFormat(){
    //21 июня, среда, 21:30
    $n = date('n',time());
    $d = date('d',time());
    $time = date('H:i',time());
    $w = date('w',time());
    $weeks = array('понедельник','вторник','среда','четверг','пятница','суббота','воскресенье');
    $monthes = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
    return $d.' '.$monthes[$n].', '.$weeks[$w].', '.$time;
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>UCMS Administrator area...</title>
<link rel="SHORTCUT ICON" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Author" content="Yesgroup">
<link rel="StyleSheet" type="text/css" href="includes/css/style.css">
<link rel="stylesheet" type="text/css" href="includes/css/ucms.css">
<link rel="stylesheet" type="text/css" href="includes/css/ucms_new.css">
<link rel="stylesheet" type="text/css" href="includes/fancybox/jquery.fancybox-1.3.4.css"/>
<style type="text/css">@import "includes/css/jquery.datepick.css";</style>

<script type="text/javascript" src="includes/js/jquery-1.5.min.js"></script>
<script type="text/javascript" src="includes/js/jquery.datepick.js"></script>
<script type="text/javascript" src="includes/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script language="javascript" type="text/javascript" src="includes/js/window.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/js/popup.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/js/drop-down_menu.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/js/ajax.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/js/fancybox.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/js/common.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/js/app.js"></script>
<script type="text/javascript" src="http://jquery-joshbush.googlecode.com/files/jquery.maskedinput-1.2.2.min.js" ></script>
<script language="JavaScript" type="text/javascript" src="includes/js/tiny_mce/tiny_mce.js"></script>
<script language="JavaScript" type="text/javascript">
	$(function() {
		$('#buttonPicker1').datepick({dateFormat: 'yyyy-mm-dd', showOnFocus: false,
	    	showTrigger: '<button type="button" class="trigger"><img width="16" height="16" border="0" alt="" src="img/calendar.gif"></button>'});

	    $('#buttonPicker2').datepick({dateFormat: 'yyyy-mm-dd', showOnFocus: false,
	    	showTrigger: '<button type="button" class="trigger"><img width="16" height="16" border="0" alt="" src="img/icons/m_tables.gif"></button>'});

	   	//$('#blanket').css('opacity','0.5').css('width','100%').css('position','absolute').css('left','0').css('top','0').css('backgroundColor','#000000');
	});
</script>
<script language="JavaScript" type="text/javascript">
jQuery(function($) {
$.mask.definitions['H']='[012]';
$.mask.definitions['M']='[012345]';
$('#begintime').mask('H9:M9');
});
</script>
<link rel="stylesheet" type="text/css" href="includes/css/ucms_new.css">


<script src="includes/js/jquery-ui-1.10.3.custom.min.js"></script>
<script>
	$(function() {
		 $("#PopUp").draggable({
		 drag:function(){
			$('.close').css({
			'position':'relative',
			'right':'-75px',
			'top':'-30px'
			});
			if(($('html').width()-($('.close').offset().left+60))<0){
			$('.close').css({
			'position':'fixed',
			'right':'0',
			'top':$('#PopUp').offset().top-30
			});
			}
		 }
		 });
		  $("#_PopUp").draggable({
		 drag:function(){
			$('.close').css({
			'position':'relative',
			'right':'-75px',
			'top':'-30px'
			});
			if(($('html').width()-($('.close').offset().left+60))<0){
			$('.close').css({
			'position':'fixed',
			'right':'0',
			'top':$('#_PopUp').offset().top-30
			});
			}
		 }
		 });
		$(".multiple_clear").click(function(){
			$(".multiple select option").removeAttr('selected');
		});
	});
	</script>
</head>
<body marginheight="0" marginwidth="0" rightmargin="0" leftmargin="0" topmargin="0" bottommargin="0" >
<div id="blanket" style="display:none;"></div>
<div id="tmpDIV"></div>
<!--div class="ucms">
    <div class="header">
        <div class="userForm">
            <span class="userForm_lt"><img src="img/ucms_img/userBg_lt.gif" /> </span>
            <div class="userFoto">
                <img src="img/ucms_img/user_foto.gif" />
            </div>
            <div class="infoSide">
                <div class="nickName">
                    <a href="#">herurg</a>
                    <span class="notice">
                        <a class="notice_msg" href="#"><span>2</span>новых уведомления</a>
                    </span>
                </div><br />

                <p class="userName">
                   <strong>Черкасов Алексей,</strong><br /> администратор
                </p>
                <div class="clear"></div>
                <p class="bot_lnks"><a href="#" class="f">Настройки</a><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>" class="s">На сайт</a><a href="?mode=logout" class="t">Выход</a></p>
            </div>

            <div class="clear"></div>
        </div>

    </div>
</div-->
	<div id="window">
		<div id="header">
			<div id="logo-text">
				<table  width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>

						<td align="left" width="100%">Система управления сайтом: <strong><?php echo $_SERVER['SERVER_NAME']; ?></strong></td>
					</tr>
				</table>
			</div>

			<div id="authorization">
				<div class="auth">
					<img src="img/ucms_img/avatar.png" />
					<p id="username">admin</p><br />
					<p id="user-status">System administrator</p>
					<a href="?mode=logout" class="logout">&nbsp</a>
				</div>

				<div class="date"><p><?php echo dateFormat();?></p></div>


			</div>


		</div>
		<div id="sub-navigation">

		</div>
