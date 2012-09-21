<?php
/**
  * kursBCA
  * Menampilkan kurs dari website BCA
  *
  * @author		: Prestanesia
  * @website	: http://prestanesia.com
  * @version	: 0.2
  * @license 	: http://www.opensource.org/licenses/bsd-license.php
**/
class kursbca extends Module
{
	private $_html = '';
	private $_postErrors = array();
	
    public function __construct()
    {
        $this->name = 'kursbca';
        $this->version = 0.2;
		$this->author = 'prestanesia.com';
		
		if (version_compare(_PS_VERSION_, 1.4) >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';		

        parent::__construct();

        /* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Kurs BCA');
        $this->description = $this->l('Menampilkan kurs dari website BCA');
		
	}

    function install()
    {
        if (!parent::install() OR !$this->registerHook('rightColumn'))
			return false;
		return true;
    }
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}	
    function hookLeftColumn($params)
    {
		global $smarty;
		$smarty->assign('this_path', $this->_path);
		return $this->display(__FILE__, 'kursbca.tpl');
	}
	
	function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}
	
}

?>
