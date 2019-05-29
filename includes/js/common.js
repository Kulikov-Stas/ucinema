function siteSearch(ev)
{
    if(ev.keyCode == 13)
    {
        var form = document.getElementById('frmSearch');
        if(form != null)
        {
            form.submit();
        }
    }
}

// Убирает пробельные символы слева
function ltrim(str) {
  return str.replace(/^\s+/, '');
}
// Убирает пробельные символы справа
function rtrim(str) {
  return str.replace(/\s+$/, '');
}
// Убирает пробельные символы с обоих концов
function trim(str) {
	return ltrim(rtrim(str));
}

// check required fields into specified tag
function CheckRequired($form)
{
    var $reqArr = $form.find('.required');
    var result = true;
    for(var i = 0; i < $reqArr.length; i++)
    {
        if(!$reqArr.eq(i).val() || $reqArr.eq(i).val() == 0)
        {
            $reqArr.eq(i).addClass('error');
            $reqArr.eq(i).val('<--Не заполнено-->');
            result = false;
        }
    }
    return result;
}
// check password fields into specified tag
function CheckRequiredPass($form)
{
    var $reqArr = $form.find('.required_pass');
    if($reqArr.eq(0).val() != $reqArr.eq(1).val())
    {
        //$reqArr.eq(i).parent('div.text-field').addClass('text-field-error').prepend('<span class="exclamation-mark"></span>');
        return false;
    }
    return true;
}
$(document).ready(function(){
    $('.required').click(function(){
        if($(this).hasClass('error'))
        {
            $(this).removeClass('error');
            $(this).val('');
        }
    });
});
