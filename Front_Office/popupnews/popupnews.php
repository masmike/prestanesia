<?php

/**
  * Popup News Module
  * Display popup news when user opening the website
  *
  * @author		: Prestanesia (prestanesia@gmail.com)
  * @website	: http://prestanesia.com
  * @version	: 1.3
**/

class PopupNews extends Module
{
	public function __construct()
    {
        $this->name = 'popupnews';
        $this->version = 1.3;
		$this->author = 'prestanesia.com';
		
		if (version_compare(_PS_VERSION_, 1.4) >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Tools';

        parent::__construct();

        $this->displayName = $this->l('Popup News');
        $this->description = $this->l('Display popup news when user opening the website.');
	
	}

    function install()
    {

        if (!parent::install() OR !$this->registerHook('footer') OR !Configuration::updateValue('POPUPNEWS_INTERVAL', '5') OR !Configuration::updateValue('POPUPNEWS_SHOWALL', '0'))
			return false;
		return true;

    }

	function uninstall()
	{
		if (!Configuration::deleteByName('POPUPNEWS_INTERVAL')
			OR !Configuration::deleteByName('POPUPNEWS_SHOWALL')
		    OR !parent::uninstall())
		return false;
		return true;
	}
	
    function hookFooter($params)
    {
		global $smarty,$page_name,$cookie;
		
		$smarty->assign('newstitle', Configuration::get('POPUPNEWS_TITLE', (int)($cookie->id_lang)));		
		$smarty->assign('news', html_entity_decode(preg_replace("(\r\n)", "", Configuration::get('POPUPNEWS_NEWS', (int)($cookie->id_lang)))));
		$smarty->assign('interval', Configuration::get('POPUPNEWS_INTERVAL'));		
		$smarty->assign('this_path', $this->_path);
		$smarty->assign('show_all', Configuration::get('POPUPNEWS_SHOWALL'));

		return $this->display(__FILE__, 'popupnews.tpl');	

	}

	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		$errors = '';
		
		if (Tools::isSubmit('submitSetting'))
		{
			
			$languages = Language::getLanguages(true);
			
			foreach ($languages as $language)
			{
				if (empty($_POST['fntitle_'.$language['id_lang']]))
					$errors .= $this->displayError($this->l('News title for language').' "'.Language::getIsoById($language['id_lang']).'" '.$this->l('can\'t be blank!'));
				if (empty($_POST['fnnews_'.$language['id_lang']]))
					$errors .= $this->displayError($this->l('News detail for language').' "'.Language::getIsoById($language['id_lang']).'" '.$this->l('can\'t be blank!'));
			}
			
			$resultTitle = array();
			$resultNews = array();
			foreach ($languages AS $language)
			{
				$resultTitle[$language['id_lang']] = $_POST['fntitle_'.$language['id_lang']];
				$newsdetail = htmlentities(preg_replace("(\r\n)", "", strtr(Tools::getValue('fnnews_'.$language['id_lang']),'"',"'")));
				$resultNews[$language['id_lang']] = $newsdetail;
			}
			
				
			if ($errors == '')
			{
				Configuration::updateValue('POPUPNEWS_TITLE', $resultTitle);
				Configuration::updateValue('POPUPNEWS_NEWS', $resultNews);
				Configuration::updateValue('POPUPNEWS_INTERVAL', $_POST['fninterval']);
				Configuration::updateValue('POPUPNEWS_SHOWALL', $_POST['showAll']);
			}
			
			$this->_html .= $errors;
			
			echo '<div class="conf confirm"><img src="../img/admin/ok.gif"/>'.$this->l('Configuration updated').'</div>';			
		}

		$this->_displayForm();
		return $this->_html;
	}		

	
	public function _displayForm()
	{
		global $cookie;
		
		$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages(true);
		$iso = Language::getIsoById((int)$cookie->id_lang);		
		
		$divLangName = 'title¤news';
		
		for($i = 5; $i < 35; $i=$i+5) {
			$cb=$cb.'<option value="'.$i.'"'.((Configuration::get('POPUPNEWS_INTERVAL') == $i) ? ' selected="selected"' : '').'>'.$i.'</option>';
		}
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->_html .= '
			<script type="text/javascript">	
				var iso = \''.(file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'\' ;
				var pathCSS = \''._THEME_CSS_DIR_.'\' ;
				var ad = \''.dirname($_SERVER['PHP_SELF']).'\' ;
			</script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
			<script language="javascript">id_language = Number('.$defaultLanguage.');</script>';
		else
		{
			$this->_html  .= '
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
				tinyMCE.init({
					mode : "textareas",
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
					theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : false,
					content_css : "'.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/global.css",
					document_base_url : "'.__PS_BASE_URI__.'",
					width: "600",
					height: "auto",
					font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",
					elements : "nourlconvert",
					entity_encoding: "raw",
					convert_urls : false,
					language : "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'"
				});
				id_language = Number('.$defaultLanguage.');
			</script>';		
		}
		
		$this->_html .= '
		<p>'.$this->l('This module is available for free. If you enjoy using this module and find it useful, please donate a token of your appreciation. Your donation will help encourage and support the plugin\'s continued development and better user support').'.</p>
		<div class="clear">&nbsp;</div>
		<p>'.$this->l('Get more Prestashop FREE modules,  articles, tips and tricks from our website').', <a href="http://prestanesia.com" target="_blank">Prestanesia.com</a>.</p>
		<div class="clear">&nbsp;</div>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" id="form" style="margin-bottom:10px;">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('News Title').'</label>
				<div class="margin-form">';
				foreach ($languages as $language)
				$this->_html .= '
					<div id="title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text" name="fntitle_'.$language['id_lang'].'" id="fntitle_'.$language['id_lang'].'" value="'.Configuration::get('POPUPNEWS_TITLE', $language['id_lang']).'" size="50" />
					</div>';	
				$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName , 'title', true).'					
					<p class="clear">'.$this->l('News Title').'</p>
				</div>
				<label>'.$this->l('News').'</label>
				<div class="margin-form">';
				foreach ($languages as $language)
				$this->_html .= '
					<div id="news_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<textarea class="rte" name="fnnews_'.$language['id_lang'].'" id="fnnews_'.$language['id_lang'].'" rows="4" cols="53">'.(Tools::getValue('fnnews', Configuration::get('POPUPNEWS_NEWS', $language['id_lang']))).'</textarea>
					</div>';
				$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'news', true).'					
					<p class="clear">'.$this->l('News content').'</p>
				</div>
				<label>'.$this->l('Interval (second)').'</label>
				<div class="margin-form">
					<select name="fninterval">'.$cb.'</select>				
					<p class="clear">'.$this->l('Interval (second)').'</p>
				</div>
				<label>'.$this->l('Show window on every page?').'</label>
				<div class="margin-form">
					<input type="checkbox" name="showAll" value="1" id="showAll" '.(Configuration::get('POPUPNEWS_SHOWALL') ? 'checked="checked"' : '').' />
					<p class="clear">'.$this->l('Show window on every page').'</p>
				</div>				
				<center><input type="submit" name="submitSetting" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>			
		</form>
		';
	}

}

?>
