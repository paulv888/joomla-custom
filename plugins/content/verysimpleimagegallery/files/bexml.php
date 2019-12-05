<?php
/*
// "Very Simple Image Gallery" Plugin for Joomla 3.1 - Version 1.6.8
// License: GNU General Public License version 2 or later; see LICENSE.txt
// Author: Andreas Berger - andreas_berger@bretteleben.de
// Copyright (C) 2013 Andreas Berger - http://www.bretteleben.de. All rights reserved.
// Project page and Demo at http://www.bretteleben.de
// ***Last update: 2013-08-15***
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.form.formfield');//import the necessary class definition for formfield
class JFormFieldbexml extends JFormField {
	
	protected $type = 'bexml';

	var	$_name = 'Very Simple Image Gallery';
	var $_version = '1.6.8';

	protected function getInput(){
		$view =$this->element['view'];
		$lang = JFactory::getLanguage();
		$lang = $lang->getTag();

		switch ($view){

		case 'intro':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".$this->_name." ".JText::_('PLG_VSIG_VERSION').": ".$this->_version."</b><br />";
            $html.=JText::_('PLG_VSIG_SUPPORT').":&nbsp;";
            $html.="<a href='http://www.bretteleben.de' target='_blank'>www.bretteleben.de</a>";
            $html.="</div>";
		break;

		case 'gallery':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_GALLERY')."</b> - ".JText::_('PLG_VSIG_GALLERY_GENERAL').".";
            $html.="</div>";
		break;

		case 'thumbs':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_THUMBNAILS')."</b> - ".JText::_('PLG_VSIG_THUMBNAILS_GENERAL').".";
            $html.="</div>";
		break;

		case 'captions':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_CAPTIONS')."</b> - ".JText::_('PLG_VSIG_CAPTIONS_GENERAL')." <a href='http://www.bretteleben.de/lang-en/joomla/very-simple-image-gallery/-anleitung-plugin-code.html' target='_blank'>".JText::_('PLG_VSIG_CAPTIONS_GENERAL_HOWTO')."</a>).";
            $html.="</div>";
		break;

		case 'links':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_LINKS')."</b> - ".JText::_('PLG_VSIG_LINKS_GENERAL')." <a href='http://www.bretteleben.de/lang-en/joomla/very-simple-image-gallery/-anleitung-plugin-code.html' target='_blank'>".JText::_('PLG_VSIG_LINKS_GENERAL_HOWTO')."</a>).";
            $html.="</div>";
		break;

		case 'sets':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_SETS')."</b> - ".JText::_('PLG_VSIG_SETS_GENERAL')." <a href='http://www.bretteleben.de/lang-en/joomla/very-simple-image-gallery/-anleitung-plugin.html' target='_blank'>".JText::_('PLG_VSIG_SETS_GENERAL_HOWTO')."</a>).";
            $html.="</div>";
		break;

		case 'gd':
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_GDLIB')."</b> - ".JText::_('PLG_VSIG_GDLIB_GENA')." <br />".JText::_('PLG_VSIG_GDLIB_GENB');
						if(function_exists("gd_info")){
            	$html.="<br />".JText::_('PLG_VSIG_GDLIB_SUPPORTED')."<br /><br />";
							$gd = gd_info();
							$be_gdarray=array(
										"gd" => "<span style='color:red'>".JText::_('PLG_VSIG_GDLIB_UNKNOWN')."</span>",
										"jpg" => "<span style='color:red'>".JText::_('PLG_VSIG_GDLIB_NOT_ENABLED')."</span>",
										"png" => "<span style='color:red'>".JText::_('PLG_VSIG_GDLIB_NOT_ENABLED')."</span>",
										"gifr" => "<span style='color:red'>".JText::_('PLG_VSIG_GDLIB_NOT_ENABLED')."</span>",
										"gifw" => "<span style='color:red'>".JText::_('PLG_VSIG_GDLIB_NOT_ENABLED')."</span>");
							foreach ($gd as $k => $v) {
								if(stristr($k,"gd")!=FALSE){$be_gdarray["gd"]=$v;}
								if((stristr($k,"jpg")!=FALSE||stristr($k,"jpeg")!=FALSE)&&$v==1&&function_exists("imagecreatefromjpeg")){$be_gdarray["jpg"]=JText::_('PLG_VSIG_GDLIB_ENABLED');}
								if(stristr($k,"png")!=FALSE&&$v==1&&function_exists("imagecreatefrompng")){$be_gdarray["png"]=JText::_('PLG_VSIG_GDLIB_ENABLED');}
								if(stristr($k,"gif read")!=FALSE&&$v==1){$be_gdarray["gifr"]=JText::_('PLG_VSIG_GDLIB_ENABLED');}
								if(stristr($k,"gif create")!=FALSE&&$v==1&&function_exists("imagecreatefromgif")){$be_gdarray["gifw"]=JText::_('PLG_VSIG_GDLIB_ENABLED');}
							}
            	$html.=JText::_('PLG_VSIG_GDLIB_GDVER').": ".$be_gdarray["gd"]."<br />";
            	$html.=JText::_('PLG_VSIG_GDLIB_JPGSUP').": ".$be_gdarray["jpg"]."<br />";
            	$html.=JText::_('PLG_VSIG_GDLIB_PNGSUP').": ".$be_gdarray["png"]."<br />";
            	$html.=JText::_('PLG_VSIG_GDLIB_GIFREAD').": ".$be_gdarray["gifr"]."<br />";
            	$html.=JText::_('PLG_VSIG_GDLIB_GIFCREATE').": ".$be_gdarray["gifw"]."<br />";
						}else{
            	$html.="<br /><span style='color:red'>".JText::_('PLG_VSIG_GDLIB_NOTSUPPORTED')."</span><br />";
						}
            $html.="<br /><a href='http://www.bretteleben.de/lang-en/joomla/very-simple-image-gallery/faq-a-troubleshooting.html#faq01' target='_blank'>".JText::_('PLG_VSIG_GDLIB_LINK')."</a>";
            $html.="</div>";
		break;

		default:
            $html="<div style='background-color:#c3d2e5;margin:0;padding:2px;display:block;clear:both;'>";
            $html.="<b>".JText::_('PLG_VSIG_OTHERSETTINGS')."</b>";
            $html.="</div>";
		break;

		}
		return $html;
	}
}