//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

function getElementsByClass(searchClass,node,tag) {
   var classElements = new Array();

   if ( node == null )
      node = document;
   if ( tag == null )
      tag = '*';
   var elements = node.getElementsByTagName(tag);
   var elemLength = elements.length;
   var pattern = new RegExp('(^|\\\\s)'+searchClass+'(\\\\s|$)');
   for (var i = 0, j = 0; i < elemLength; i++) {
      if ( pattern.test(elements[i].className) ) {
         classElements[j] = elements[i];
         j++;
      }
   }
   return classElements;
}

function town() {	
	$width = document.getElementById('town').scrollWidth;
	$width_el = Math.floor($width/10);
	var $i=1;
	while(document.getElementById('town'+$i)) {
		document.getElementById('town'+$i).style.width = $width_el;
		$i++}
}



function pad() {
	var $a = 0;
	var $b = getElementsByClass('pad');
	while($b[$a])
		{
			if (document.body.clientWidth < 1000) {
				$b[$a].style.margin = 0;				
			}
			if (document.body.clientWidth < 1080 && document.body.clientWidth > 1000 ) {
				//$pad_left =  Math.ceil((document.body.clientWidth-1000)/2);
				//$pad_right =  Math.floor((document.body.clientWidth-1000)/2);
				//$b[$a].style.marginLeft = $pad_left+'px';
				//$b[$a].style.marginRight = $pad_right+'px';
				$b[$a].style.width = '1000px';
				$b[$a].style.marginLeft = 'auto';
				$b[$a].style.marginRight = 'auto';
				
			}
			if (document.body.clientWidth > 1079) {
				$b[$a].style.width = 'auto';
				$b[$a].style.marginLeft = 40+'px';
				$b[$a].style.marginRight = 40+'px';
			}
			$a++;
		}
}
 jsHover = function() {
 if (navigator.userAgent.indexOf("MSIE")>0){
    var hEls = document.getElementById("menu").getElementsByTagName("li");
    for (var i=0, len=hEls.length; i<len; i++) {
      hEls[i].onmouseover=function() { this.className+=" jshover"; }
      hEls[i].onmouseout=function() { this.className=this.className.replace(" jshover", ""); }
    }}
  }  
function ahover() {
	
	var $tag_a = document.getElementById("menu").getElementsByTagName("A");
    for (var i=0, len=$tag_a.length; i<len; i++) {
		if ($tag_a[i].className=='menu_item') {
			$tag_a[i].onmouseover=function() { 
				this.className+=" ahover"; 
				$img = this.getElementsByTagName("IMG");
				$img[0].src = '/siteimg/menu_item_shad_top.png';
				//$img[1].style.width = $img[0].scrollWidth+4+'px';
				$img[1].src = '/siteimg/menu_item_shad_mid.png';
				$img[2].src = '/siteimg/menu_item_shad_bot.png';
			}
      		$tag_a[i].onmouseout=function() { 
				this.className=this.className.replace(" ahover", ""); 
				$img = this.getElementsByTagName("IMG");
				$img[0].src = '/siteimg/menu_item_top.png';
				$img[1].src = '/siteimg/menu_item_mid.png';
				$img[2].src = '/siteimg/menu_item_bot.png';
			}
		}
     
    }  
}

function preload() {
	names = new Array ("/siteimg/menu_item_shad_top.png",
					   "/siteimg/menu_item_shad_mid.png",
					   "/siteimg/menu_item_shad_bot.png",
					   "/siteimg/home_hov.gif",
					   "/siteimg/mail_hov.gif");
	tmp = new Array ();
	for (i in names) {
		tmp[i] = new Image();
		tmp[i].src = names[i];
	}
}

function resize_menu() {
	if (navigator.userAgent.indexOf("MSIE")>0){
		var classes = getElementsByClass('menu_el_bgr_img');	
		for (var i=0, len=classes.length; i<len; i++) {
			classes[i].style.height = classes[i].parentNode.scrollHeight;
		}
	}
}