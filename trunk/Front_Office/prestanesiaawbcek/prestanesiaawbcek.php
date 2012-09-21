<?php
/*
*
*	Prestanesia AWB Cek Module
*	by Prestanesia.com, 2012.
*	http://prestanesia.com
*	ym: prestanesia | gtalk : prestanesia | twitter : @prestanesia
*		
*	v 1.0
*	v 1.1 - cek di page sendiri
*/

if (!defined('_PS_VERSION_'))
	exit;

class PrestanesiaAWBCek extends Module
{

	public function __construct()
	{
		$this->name = 'prestanesiaawbcek';
		$this->tab = 'shipping_logistics';
		$this->version = '1.1';
		$this->author = 'Prestanesia.com';
		$this->need_instance = 0;
		
		parent::__construct ();

		$this->displayName = $this->l('Prestanesia AWB Check');
		$this->description = $this->l('Check JNE/TIKI Airway bill');
	}

	public function install(){return (parent::install() AND $this->registerHook('rightColumn'));}
	
	public function uninstall()	{return true;}

	public function cekAWB()
	{
		global $smarty;
		if (Tools::isSubmit('awb'))
		{
			$awb=Tools::getValue('txtawb');
		
			$html=$this->scrape('http://jne.co.id/index.php?mib=tracking.detail&awb='.$awb);
			preg_match("/<table width=\"100%\" border=\"0\" cellspacing=\"1\" bgcolor=\"#fff\">.*?<\/[\s]*table>/s", $html, $table_html);
			$smarty->assign('tab1', $table_html[0]);
			preg_match("/<table width=\"100%\" border=0 cellspacing=1 bgcolor=\"#fff\">.*?<\/[\s]*table>/s", $html, $table_html);
			$smarty->assign('tab2', $table_html[0]);
			preg_match("/  <table width=\"100%\" border=0 cellspacing=1 bgcolor=\"#fff\">.*?<\/[\s]*table>/s", $html, $table_html);
			$smarty->assign('tab3', $table_html[0]);
		}
		echo $this->display(__FILE__, 'cekawb.tpl');
	}
	
	public function hookRightColumn($params)
	{
		global $smarty;
		$smarty->assign('this_path_ssl', Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/cekawb.php');
		return $this->display(__FILE__, 'prestanesiaawbcek.tpl');
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	private function scrape($url){
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:14.0) Gecko/20100101 Firefox/14.0.1");
		
		$content = curl_exec($curl);
		curl_close($curl);
		
		return $content;
	}	
	
}

