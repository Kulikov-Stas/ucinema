<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>UCMS Administrator area...</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name="Author" content="Pavel Golovenko" />
<link rel="stylesheet" href="includes/css/auth.css"/>
<!--fish-->
  
  <link href="/css/admin.css" media="screen" rel="stylesheet" type="text/css" />
<!--fish end-->	

</head>
<body>
<div class="ltCol">
    	<div>
        	<a href="javascript:history.go(-1)" class="goBack">Вернуться</a><br />
            <a href="http://<?php echo $_SERVER['HTTP_HOST'];?>" class="adr">www.<?php echo $_SERVER['HTTP_HOST'];?></a>
        </div>
        <form class="langForm" method="POST">
            <select name="lang" onchange="this.form.submit()">
                <option>Русский</option>                
            </select>
        </form>
    </div>
            <div class="rtCol">
                <h1>Система управления</h1>
                <p class="ooo"><a href="/">сайтом <?php echo $_SERVER['HTTP_HOST'];?></a></p>
                <h5>Для доступа к системе авторизируйтесь:</h5>
                
                <form class="formBg" method="POST" name="auth_form" id="auth_form">
                <input type="hidden" name="post_auth" value="yes" />
                    <table>
                        <tr>
                            <td width="70px"><label>Логин</label></td>
                            <td><input type="text" name="login" /></td>
                        </tr>
                        <tr>
                            <td><label>Пароль</label></td>
                            <td><input type="password" name="pass" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="checkbox" name="remember" value="yes" checked="checked" class="chkbx"/><span>Запомнить</span></td>
                        </tr>
                    </table>
                    <input type="submit" value="" class="submit"/>
                </form>
            </div>
            
            <div class="footer">
                <div class="ltCol" style="margin-top: 0px;">
                   <a href="http://www.yesgroup.com.ua">
                        <img src="img/auth/sm_logo.png"/>
                        Copyright © 2005—<?php echo date('Y', time()); ?> <span>www.yesgroup.com.ua</span>
                   </a>
                </div>
            </div>  
</body>
<!--<body>	
	<div class="ltCol">
    	<div>
        	<a href="#" class="goBack">Вернуться</a><br />
            <a href="http://<?php echo $_SERVER['HTTP_HOST'];?>" class="adr">www.<?php echo $_SERVER['HTTP_HOST'];?></a>
        </div>
        <form class="langForm" method="POST">
            <select name="lang" onchange="this.form.submit()">
                <option>Русский</option>
                <option>English</option>
                <option>Український</option>
            </select>
        </form>
    </div>
            <div class="rtCol">
                <h1>Система управления</h1>
                <p class="ooo"><a href="#">сайтом компании ООО «Название компании»</a></p>
                <h5>Для доступа к системе авторизируйтесь:</h5>
                
                <form class="formBg" method="POST" name="auth_form" id="auth_form">
                <input type="hidden" name="post_auth" value="yes" />
                    <table>
                        <tr>
                            <td width="70px"><label>Логин</label></td>
                            <td><input type="text" name="login" /></td>
                        </tr>
                        <tr>
                            <td><label>Пароль</label></td>
                            <td><input type="password" name="pass" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="checkbox" name="remember" value="yes" checked="checked" class="chkbx"/><span>Запомнить</span></td>
                        </tr>
                    </table>
                    <input type="submit" value="" class="submit"/>
                </form>
            </div>
            
            <div class="footer">
                <div class="ltCol" style="margin-top: 0px;">
                   <a href="http://www.yesgroup.com.ua">
                        <img src="img/auth/sm_logo.gif"/>
                        Copyright © 2005—<?php echo date('Y', time()); ?> <span>www.yesgroup.com.ua</span>
                   </a>
                </div>
            </div>



</body>-->

</html>