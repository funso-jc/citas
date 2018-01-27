<?php

//Copyright (C) 2000-2006  Antonio Grandío Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adrián http://www.inmaecharri.com

//This file is part of Catwin.

//CatWin is free software; you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation; either version 2 of the License, or
//(at your option) any later version.

//CatWin is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details:
//http://www.gnu.org/copyleft/gpl.html

//You should have received a copy of the GNU General Public License
//along with Catwin Net; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

session_start();

include("a_cookies.php");

extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
include("funciones.php");
/*
include ("calendario/calendario.php");
include ("popcalendario/escribe_formulario.php");  		// cambiando fechas
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-ES" lang="es">

<head>
<title>_.:| Fundaci&oacute;n Social UCLA FUNSOUCLA :._</title>
<link rel="shortcut icon" href="animated_favicon1.gif" >
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="pragma" content="no-cache" />

<link rel="stylesheet" type="text/css" href="estilo0.css" media="screen" />
<!-- <link rel="stylesheet" type="text/css" href="estilo<php if (!$c) {$c = 1;} echo $c;>.css" media="screen" /> -->
<!-- <link rel="stylesheet" type="text/css" href="DatePicker.css" media="screen" /> // cambiando fechas -->
<link rel="stylesheet" type="text/css" href="estiloimpr.css" media="print" />
<script language="Javascript" src="javascript.js" type='text/javascript'></script>
<!-- 
// cambiando fechas 
<script language="javascript" src="Acrobat/calendario555.js"></script>
<script language="JavaScript" src="popcalendario/popcalendar.js"></script>
<script language="JavaScript" src="calendario/calender.js"></script>  -->
<script type="text/javascript" src="jquery-1.2.1.pack.js"></script>
<!-- 
// cambiando fechas 
<script language="JavaScript" src="calendarioventana.js"></script>

<!-- <script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="lang/calendar-es.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="calender.css" media="print" /> // cambiando fehas -->
<link rel="stylesheet" type="text/css" media="all" href="calendar-green.css" title="green" />

<!-- <link rel="stylesheet" type="text/css" media="all" href="calendar-blue.css" title="green" /> -->
<!-- main calendar program -->
<script type="text/javascript" src="calendar.js"></script>
<!-- language for the calendar -->
<script type="text/javascript" src="lang/calendar-es.js"></script>
<!-- the following script defines the Calendar.setup helper function, which makes
      adding a calendar a matter of 1 or 2 lines of code. -->
<script type="text/javascript" src="calendar-setup.js"></script>

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"-->

    <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet">  
   <!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script> 
  <script type="text/javascript" src="bootstrap/js/moment.min.js"></script>
    <script src="bootstrap/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/validation.min.js"></script>
<script type="text/javascript" src="bootstrap/js/daterangepicker.js"></script> 
<link rel="stylesheet" type="text/css" href="bootstrap/css/daterangepicker.css" /> 
    <!-- script src="bootstrap/js/bootstrap-datetimepicker.es.js"></script> -->

<!-- los enlaces para menu multinivel -->
<!-- SmartMenus jQuery Bootstrap Addon CSS -->
<link href="bootstrap/css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
<!-- SmartMenus jQuery plugin -->
<script type="text/javascript" src="bootstrap/js/jquery.smartmenus.js"></script>
<!-- SmartMenus jQuery Bootstrap Addon -->
<script type="text/javascript" src="bootstrap/js/jquery.smartmenus.bootstrap.js"></script>
<!-- fin de los enlaces para menu multinivel -->
<script src="bootstrap/js/bootstrap.min.js"></script>


<script type="text/javascript" src="refrescar.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<style type="text/css">
	/* 
	General styles for this example page */
/*	html{
		height:100%;
	}
/*
	body{
		font-family: Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif;
		font-size:0.8em;
		margin:0px;
		padding:0px;
		text-align:center;
		/* background-color:#E2EBED; /
		background-color:#FFFFFF;
		height:100%;
	}*/
	
	p{
		margin-top:5px;
		margin-bottom:10px;
	}
	
	#mainContainer{
		width:510px;
		margin:0 auto;
		text-align:left;
		background-color: #FFF;
		padding-left:16px;
		padding-right:16px;
		padding-bottom:5px;	
	}
	
	#dhtmlgoodies_menu img{
		border:0px;
	}

	/* End general styles for this example page */
	/* General configuration CSS */
	
	#dhtmlgoodies_menu ul li ul{
		display:none;	/* Needed to display ok in Opera */
	}
		
	#dhtmlgoodies_menu{
		visibility:hidden;	
	}
	#dhtmlgoodies_menu ul{
		margin:0px;	/* No indent */
		padding:0px;	/* No indent */
	}
	#dhtmlgoodies_menu li{
		list-style-type:none;	/* No bullets */
	}	
	#dhtmlgoodies_menu a{

		margin:0px;
		padding:0px;
	}
	/* End general configuration CSS */
	
	
	/* Cosmetic */
	
	/***********************************************************************
		CSS - MENU BLOCKS
	 	General rules for all menu blocks (group of sub items) 
	***********************************************************************/
	#dhtmlgoodies_menu ul{
		border:1px solid #000;
		background-color:#FFF;
		padding:1px;
	}
		
	#dhtmlgoodies_menu ul.menuBlock1{	/* Menu bar - main menu items */
		border:0px;
		padding:1px;
		border:1px solid #317082;
		background-color:#E2EBED;
		overflow:visible;
	}
	#dhtmlgoodies_menu ul.menuBlock2{	/* Menu bar - main menu items */
		border:0px;
		padding:0px;
		border:1px solid #555;
	}
	
	/***********************************************************************
		CSS - MENU ITEMS
	 	Here, you could assign rules to the menu items at different depths.
	***********************************************************************/
	/* General rules for all menu items */
	#dhtmlgoodies_menu a{
		color: #000;
		text-decoration:none;
		padding-left:2px;
		padding-right:2px;
	
	}
	
	/*
	Main menu items 
	*/
	
	#dhtmlgoodies_menu .currentDepth1{
		padding-left:5px;
		padding-right:5px;
		border:1px solid #E2EBED;
	}
	#dhtmlgoodies_menu .currentDepth1over{
		padding-left:5px;
		padding-right:5px;
		background-color:#317082;
		border:1px solid #000;
		
		
	}
	#dhtmlgoodies_menu .currentDepth1 a{
		font-weight:bold;
	}
	#dhtmlgoodies_menu .currentDepth1over a{	/* Text rules */
		color:#FFF;
		font-weight:bold;
	}
	
	/* Sub menu depth 1 */
	#dhtmlgoodies_menu .currentDepth2{
		padding-right:2px;
		border:1px solid #FFF;
	}
	#dhtmlgoodies_menu .currentDepth2over{
		padding-right:2px;
		background-color:#E2EBED;
		border:1px solid #000;
	}	
	#dhtmlgoodies_menu .currentDepth2over a{	/* Text rules */
		color:#000;
	}	
	/* Sub menu depth 2 */
	#dhtmlgoodies_menu .currentDepth3{
		padding-right:2px;
		border:1px solid #FFF;
	}
	#dhtmlgoodies_menu .currentDepth3over{
		padding-right:2px;
		background-color:#EDE3EB;
		border:1px solid #000;
	}
	/* Sub menu depth 3 */
	#dhtmlgoodies_menu .currentDepth4{
		padding-right:2px;
		border:1px solid #FFF;
	}
	#dhtmlgoodies_menu .currentDepth4over{
		padding-right:2px;
		background-color:#EBEDE3;
		border:1px solid #000;
	}	
	</style>
	<script type="text/javascript">
   /************************************************************************************************************ 
   (C) www.dhtmlgoodies.com, October 2005 
    
   This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.    
    
   Terms of use: 
   You are free to use this script as long as the copyright message is kept intact. However, you may not 
   redistribute, sell or repost it without our permission. 
    
   Thank you! 
    
   www.dhtmlgoodies.com 
   Alf Magne Kalleland 
    
   ************************************************************************************************************/    
        
   var dhtmlgoodies_menuObj;   // Reference to the menu div 
   var currentZIndex = 1000; 
   var liIndex = 0; 
   var visibleMenus = new Array(); 
   var activeMenuItem = false; 
   var timeBeforeAutoHide = 1200; // Microseconds from mouse leaves menu to auto hide. 
   var dhtmlgoodies_menu_arrow = 'imagenes/next.gif'; 
//   var dhtmlgoodies_menu_arrow = 'http://www.dhtmlgoodies.com/scripts/dhtmlgoodies-menu2/images/arrow.gif'; 
    
   var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false; 
   var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox')>=0?true:false; 
   var navigatorVersion = navigator.appVersion.replace(/.*?MSIE ([0-9]\.[0-9]).*/g,'$1')/1; 
   var menuBlockArray = new Array(); 
   var menuParentOffsetLeft = false;    


    // {{{ getStyle() 
   /** 
   * Return specific style attribute for an element 
   * 
   * @param Object el = Reference to HTML element 
   * @param String property = Css property 
   * @private 
   */        
   function getStyle(el,property) 
   {        

      if (document.defaultView && document.defaultView.getComputedStyle) { 

         var retVal = null;              
         var comp = document.defaultView.getComputedStyle(el, ''); 
         if (comp){ 
            retVal = comp[property]; 
              
            if(!retVal){ 
               var comp = document.defaultView.getComputedStyle(el, null); 
               retVal = comp.getPropertyCSSValue(property); 
            }          
         }    

         if(retVal==null)retVal=''; 
          
         return el.style[property] || retVal; 
      } 
      if (document.documentElement.currentStyle && MSIE){    
         var value = el.currentStyle ? el.currentStyle[property] : null; 
         return ( el.style[property] || value ); 
                                              
      } 
      return el.style[property];              
   } 
      
   function getTopPos(inputObj) 
   { 
   	var origInputObj = inputObj;
 
     var returnValue = inputObj.offsetTop; 
     if(inputObj.tagName=='LI' && inputObj.parentNode.className=='menuBlock1'){    
        var aTag = inputObj.getElementsByTagName('A')[0]; 
        if(aTag)returnValue += aTag.parentNode.offsetHeight; 
     } 
     var topOfMenuReached = false; 
     while((inputObj = inputObj.offsetParent) != null){ 
        if(inputObj.parentNode.id=='dhtmlgoodies_menu')topOfMenuReached=true; 
        if(topOfMenuReached && !inputObj.className.match(/menuBlock/gi) || (!MSIE && origInputObj.parentNode.className=='menuBlock1')){ 
           var style = getStyle(inputObj,'position'); 
           if(style=='absolute' || style=='relative'){                
              return returnValue;            
           } 
        } 
          
        returnValue += inputObj.offsetTop;          
     } 

     return returnValue; 
   } 
    
   function getLeftPos(inputObj) 
   { 
     var returnValue = inputObj.offsetLeft; 
      
     var topOfMenuReached = false; 
     while((inputObj = inputObj.offsetParent) != null){ 
       if(inputObj.parentNode.id=='dhtmlgoodies_menu')topOfMenuReached=true; 
        if(topOfMenuReached && !inputObj.className.match(/menuBlock/gi)){ 
           var style = getStyle(inputObj,'position'); 
           if(style=='absolute' || style=='relative')return returnValue; 
        } 
      
        returnValue += inputObj.offsetLeft; 
     } 
     return returnValue; 
   } 


    
   function showHideSub() 
   { 

      var attr = this.parentNode.getAttribute('currentDepth'); 
      if(navigator.userAgent.indexOf('Opera')>=0){ 
         attr = this.parentNode.currentDepth; 
      } 
        
      this.className = 'currentDepth' + attr + 'over'; 
        
      if(activeMenuItem && activeMenuItem!=this){ 
         activeMenuItem.className=activeMenuItem.className.replace(/over/,''); 
      } 
      activeMenuItem = this; 
    
      var numericIdThis = this.id.replace(/[^0-9]/g,''); 
      var exceptionArray = new Array(); 
      // Showing sub item of this LI 
      var sub = document.getElementById('subOf' + numericIdThis); 
      if(sub){ 
         visibleMenus.push(sub); 
         sub.style.display=''; 
         sub.parentNode.className = sub.parentNode.className + 'over'; 
         exceptionArray[sub.id] = true; 
      }    
        
      // Showing parent items of this one 
        
      var parent = this.parentNode; 
      while(parent && parent.id && parent.tagName=='UL'){ 
         visibleMenus.push(parent); 
         exceptionArray[parent.id] = true; 
         parent.style.display=''; 
          
         var li = document.getElementById('dhtmlgoodies_listItem' + parent.id.replace(/[^0-9]/g,'')); 
         if(li.className.indexOf('over')<0)li.className = li.className + 'over'; 
         parent = li.parentNode; 
          
      } 

          
      hideMenuItems(exceptionArray); 



   } 

   function hideMenuItems(exceptionArray) 
   { 
      /* 
      Hiding visible menu items 
      */ 
      var newVisibleMenuArray = new Array(); 
      for(var no=0;no<visibleMenus.length;no++){ 
         if(visibleMenus[no].className!='menuBlock1' && visibleMenus[no].id){ 
            if(!exceptionArray[visibleMenus[no].id]){ 
               var el = visibleMenus[no].getElementsByTagName('A')[0]; 
               visibleMenus[no].style.display = 'none'; 
               var li = document.getElementById('dhtmlgoodies_listItem' + visibleMenus[no].id.replace(/[^0-9]/g,'')); 
               if(li.className.indexOf('over')>0)li.className = li.className.replace(/over/,''); 
            }else{              
               newVisibleMenuArray.push(visibleMenus[no]); 
            } 
         } 
      }        
      visibleMenus = newVisibleMenuArray;        
   } 
    
    
    
   var menuActive = true; 
   var hideTimer = 0; 
   function mouseOverMenu() 
   { 
      menuActive = true;        
   } 
    
   function mouseOutMenu() 
   { 
      menuActive = false; 
      timerAutoHide();    
   } 
    
   function timerAutoHide() 
   { 
      if(menuActive){ 
         hideTimer = 0; 
         return; 
      } 
        
      if(hideTimer<timeBeforeAutoHide){ 
         hideTimer+=100; 
         setTimeout('timerAutoHide()',99); 
      }else{ 
         hideTimer = 0; 
         autohideMenuItems();    
      } 
   } 
    
   function autohideMenuItems() 
   { 
      if(!menuActive){ 
         hideMenuItems(new Array());    
         if(activeMenuItem)activeMenuItem.className=activeMenuItem.className.replace(/over/,'');        
      } 
   } 
    
    
   function initSubMenus(inputObj,initOffsetLeft,currentDepth) 
   {    
      var subUl = inputObj.getElementsByTagName('UL'); 
      if(subUl.length>0){ 
         var ul = subUl[0]; 
          
         ul.id = 'subOf' + inputObj.id.replace(/[^0-9]/g,''); 
         ul.setAttribute('currentDepth' ,currentDepth); 
         ul.currentDepth = currentDepth; 
         ul.className='menuBlock' + currentDepth; 
         ul.onmouseover = mouseOverMenu; 
         ul.onmouseout = mouseOutMenu; 
         currentZIndex+=1; 
         ul.style.zIndex = currentZIndex; 
         menuBlockArray.push(ul); 
         ul = dhtmlgoodies_menuObj.appendChild(ul); 
         var topPos = getTopPos(inputObj); 
         var leftPos = getLeftPos(inputObj)/1 + initOffsetLeft/1;          
         
         ul.style.position = 'absolute'; 
         ul.style.left = leftPos + 'px'; 
         ul.style.top = topPos + 'px'; 
         var li = ul.getElementsByTagName('LI')[0]; 
         while(li){ 
            if(li.tagName=='LI'){    
               li.className='currentDepth' + currentDepth;                
               li.id = 'dhtmlgoodies_listItem' + liIndex; 
               liIndex++;              
               var uls = li.getElementsByTagName('UL'); 
               li.onmouseover = showHideSub; 

               if(uls.length>0){ 
                  var offsetToFunction = li.getElementsByTagName('A')[0].offsetWidth+2; 
                  if(navigatorVersion<6 && MSIE)offsetToFunction+=15;   // MSIE 5.x fix 
                  initSubMenus(li,offsetToFunction,(currentDepth+1)); 
               }    
               if(MSIE){ 
                  var a = li.getElementsByTagName('A')[0]; 
                  a.style.width=li.offsetWidth+'px'; 
                  a.style.display='block'; 
               }                
            } 
            li = li.nextSibling; 
         } 
         ul.style.display = 'none';    
         if(!document.all){ 
            //dhtmlgoodies_menuObj.appendChild(ul); 
         } 
      }    
   } 


   function resizeMenu() 
   { 
      var offsetParent = getLeftPos(dhtmlgoodies_menuObj); 
        
      for(var no=0;no<menuBlockArray.length;no++){ 
         var leftPos = menuBlockArray[no].style.left.replace('px','')/1; 
         menuBlockArray[no].style.left = leftPos + offsetParent - menuParentOffsetLeft + 'px'; 
      } 
      menuParentOffsetLeft = offsetParent; 
   } 
    
   /* 
   Initializing menu 
   */ 
   function initDhtmlGoodiesMenu() 
   { 
      dhtmlgoodies_menuObj = document.getElementById('dhtmlgoodies_menu'); 
        
        
      var aTags = dhtmlgoodies_menuObj.getElementsByTagName('A'); 
      for(var no=0;no<aTags.length;no++){          

         var subUl = aTags[no].parentNode.getElementsByTagName('UL'); 
         if(subUl.length>0 && aTags[no].parentNode.parentNode.parentNode.id != 'dhtmlgoodies_menu'){ 
            var img = document.createElement('IMG'); 
            img.src = dhtmlgoodies_menu_arrow; 
            aTags[no].appendChild(img);              

         } 

      } 
              
      var mainMenu = dhtmlgoodies_menuObj.getElementsByTagName('UL')[0]; 
      mainMenu.className='menuBlock1'; 
      mainMenu.style.zIndex = currentZIndex; 
      mainMenu.setAttribute('currentDepth' ,1); 
      mainMenu.currentDepth = '1'; 
      mainMenu.onmouseover = mouseOverMenu; 
      mainMenu.onmouseout = mouseOutMenu;        

      var mainMenuItemsArray = new Array(); 
      var mainMenuItem = mainMenu.getElementsByTagName('LI')[0]; 
      mainMenu.style.height = mainMenuItem.offsetHeight + 2 + 'px'; 
      while(mainMenuItem){ 
          
         mainMenuItem.className='currentDepth1'; 
         mainMenuItem.id = 'dhtmlgoodies_listItem' + liIndex; 
         mainMenuItem.onmouseover = showHideSub; 
         liIndex++;              
         if(mainMenuItem.tagName=='LI'){ 
            mainMenuItem.style.cssText = 'float:left;';    
            mainMenuItem.style.styleFloat = 'left'; 
            mainMenuItemsArray[mainMenuItemsArray.length] = mainMenuItem; 
            initSubMenus(mainMenuItem,0,2); 
         }          
          
         mainMenuItem = mainMenuItem.nextSibling; 
          
      } 

      for(var no=0;no<mainMenuItemsArray.length;no++){ 
         initSubMenus(mainMenuItemsArray[no],0,2);          
      } 
        
      menuParentOffsetLeft = getLeftPos(dhtmlgoodies_menuObj);    
      window.onresize = resizeMenu;    
      dhtmlgoodies_menuObj.style.visibility = 'visible';    
   } 
	window.onload = initDhtmlGoodiesMenu;
	</script>


<?php
/*
<link href="estilda.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="acc_calendar/acc_calendar.js"></script>
*/ ?>

<?php
/* 
<script language="Javascript" src="DatePicker.js" type='text/javascript'></script>
<script language="Javascript" src="codigo.js" type='text/javascript'></script>

<link rel=stylesheet type="text/css" href="s.css">
<script language="javascript1.2" src="codethatcalendarstd.js"></script>
<script language="javascript1.2" src="scroller_ex.js"></script></head>

*/
?>
</head>
