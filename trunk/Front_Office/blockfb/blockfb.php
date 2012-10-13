<?php

if (!defined('_PS_VERSION_'))
	exit;

class BlockFB extends Module
{
	
	function __construct()
	{
		$this->name = 'blockfb';
		$this->tab = 'front_office_features';
		$this->version = 1.0;
		$this->author = 'Prestanesia.com';
		$this->need_instance = 0;

		parent::__construct();
		
		$this->displayName = $this->l('Block Facebook');
		$this->description = $this->l('Block to display Facebook widget');

	}

	function install()
	{
		if (!parent::install() || !$this->registerHook('displayLeftColumn'))
			return false;
		return true;
	}
	
	function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}
	
	function hookDisplayHome($params)
	{
		global $smarty;
		$smarty->assign('url', Configuration::get('BLOCKFB_URL'));
		$smarty->assign('width', Configuration::get('BLOCKFB_WIDTH'));
		
		return $this->display(__FILE__, 'blockfb.tpl');
	}
	
	function hookDisplayLeftColumn($params)
	{
		return $this->hookDisplayHome($params);
	}
	
	function hookDisplayRightColumn($params)
	{
		return $this->hookDisplayHome($params);
	}
	
	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submit'))
		{
			$url = Tools::getValue('url');
			$width = (int)Tools::getValue('width');

			if ($width == 0)
				$width = 191;
			elseif (!isset($url) || strlen($url)==0)
				$output .= '<div class="alert error">'.$this->l('URL cant be blank !.').'</div>';
			else
			{
				Configuration::updateValue('BLOCKFB_URL', $url);
				Configuration::updateValue('BLOCKFB_WIDTH', (int)($width));

				$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
			}
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		return '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('URL').'</label>
				<div class="margin-form">
					<input type="text" name="url" value="'.Configuration::get('BLOCKFB_URL').'" />
					<p class="clear">'.$this->l('Facebook fan page URL').'</p>
				</div>
				<label>'.$this->l('Width').'</label>
				<div class="margin-form">
					<input type="text" name="width" value="'.Configuration::get('BLOCKFB_WIDTH').'" />
					<p class="clear">'.$this->l('Block width').'</p>
				</div>
				<center><input type="submit" name="submit" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
	}	
	
}
