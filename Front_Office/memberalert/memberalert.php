<?php
/**
  * Member Alert Module
  * Notify site admin (by email) for every new member registration
  *
  * @author		: Prestanesia (prestanesia@gmail.com)
  * @website	: http://prestanesia.com
  * @version	: 1.1
**/
class MemberAlert extends Module
{
	private $_merchant_mails;
	const __MA_MAIL_DELIMITOR__ = ',';
	
    public function __construct()
    {
        $this->name = 'memberalert';
        $this->version = 1.1;
		$this->author = 'prestanesia.com';
		if (version_compare(_PS_VERSION_, 1.4) >= 0)
			$this->tab = 'administration';
		else
			$this->tab = 'Tools';
			
        parent::__construct();

		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Member Alert');
        $this->description = $this->l('Send email notification to site admin for every new user registration');
		$this->full_url = _MODULE_DIR_.$this->name.'/';
						
		if (!is_null(Configuration::get('MA_MERCHANT_MAILS')) && Configuration::get('MA_MERCHANT_MAILS')!='') 
			$this->_merchant_mails = Configuration::get('MA_MERCHANT_MAILS');		
		else
			$this->_merchant_mails = Configuration::get('PS_SHOP_EMAIL');		
	}

    function install()
    {
        if (!parent::install() OR !$this->registerHook('createAccount'))
			return false;
		return true;
    }
	
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}	

	function hookCreateAccount($params)
	{
		global $cookie;
		
		$postVars = $params['_POST'];		
		if (empty($postVars))
			return false;		
		
		$data = array(
						 '{firstname}' => $postVars['firstname']
						,'{lastname}' => $postVars['lastname']
						,'{email}' => $postVars['email']
						,'{newsletter}' => ($postVars['newsletter']==1 ? $this->l('Yes') : $this->l('No'))
						,'{birthday}' => $postVars['months'].'/'.$postVars['days'].'/'.$postVars['years']
						,'{address1}' => $postVars['address1']
						,'{address2}' => $postVars['address2']
						,'{postcode}' => $postVars['postcode']
						,'{city}' => $postVars['city']
						,'{country}' => Country::getNameById(intval($cookie->id_lang), intval($postVars['id_country']))	
						,'{state}' => State::getNameById(intval($postVars['id_state']))	
						,'{phone}' => $postVars['phone']
						,'{phone_mobile}' => $postVars['phone_mobile']
						,'{company}' => $postVars['company']
						,'{other}' => $postVars['other']
					);
		
		Mail::Send(intval(Configuration::get('PS_LANG_DEFAULT')), 'memberalert', $this->l('New member registration!'), $data, explode(self::__MA_MAIL_DELIMITOR__, $this->_merchant_mails), NULL, strval(Configuration::get('PS_SHOP_EMAIL')), strval(Configuration::get('PS_SHOP_NAME')), NULL, NULL, dirname(__FILE__).'/mails/');

	}
	
}

?>
