<?php
/*
*
*	Prestanesia Our Carrier Module
*	by Prestanesia.com, 2012.
*	http://prestanesia.com
*	ym: prestanesia | gtalk : prestanesia | twitter : @prestanesia
*		
*	v 1.0
*/

if (!defined('_PS_VERSION_'))
	exit;

class PrestanesiaOurCarrier extends Module
{

	public function __construct()
	{
		$this->name = 'prestanesiaourcarrier';
		$this->tab = 'shipping_logistics';
		$this->version = '1.0';
		$this->author = 'Prestanesia.com';
		$this->need_instance = 0;
		
		parent::__construct ();

		$this->displayName = $this->l('Prestanesia Our Carrier');
		$this->description = $this->l('Display active carrier');
	}

	public function install(){return (parent::install() AND $this->registerHook('rightColumn'));}
	
	public function uninstall()	{return true;}

	public function hookRightColumn($params)
	{
		global $smarty;
		$smarty->assign('carriers', Carrier::getCarriers((int)(Configuration::get('PS_LANG_DEFAULT')), true , false,false, NULL, Carrier::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE));
		return $this->display(__FILE__, 'prestanesiaourcarrier.tpl');
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}
	
}

