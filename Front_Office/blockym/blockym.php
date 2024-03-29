<?php

if (!defined('_PS_VERSION_'))
	exit;
	
class BlockYM extends Module
{	
	
	private $_html = '';
	private $_postErrors = array();
	
	function __construct()
 	{
 	 	$this->name = 'blockym';
 	 	$this->version = '0.5';
		$this->author = 'Prestanesia.com';
		$this->need_instance = 0;		
		$this->tab = 'front_office_features';

		parent::__construct();
		
		$this->displayName = $this->l('Block YM+');
		$this->description = $this->l('Display YM! online status');
		
 	}

	function install()
	{
	 	if (!parent::install() OR !$this->registerHook('displayLeftColumn') OR !$this->registerHook('displayHeader'))
	 		return false;
	 	return true;
	}
	
	public function initConfig() {
		Configuration::updateValue('PS_YM_DATA', NULL);
		Configuration::updateValue('PS_YM_TITLE', 'Online Chat');
		Configuration::updateValue('PS_YM_BLOCK', 1);
		return true;
	}
	
	public function uninstall()
	{
		if (!parent::uninstall() OR !Configuration::deleteByName('PS_YM_DATA') OR !Configuration::deleteByName('PS_YM_TITLE')  OR !Configuration::deleteByName('PS_YM_BLOCK'))
			return false;
			
		return true;	
	}
	
	private function dataList()
	{	
		$_list  = '<table cellspacing="0" cellpadding="0" class="table" style="width: 50em;">';
		$_list .= '<thead><tr><th>Title</th><th>Yahoo ID</th><th>Icon</th><th width="25">&nbsp;</th></tr></thead>';
		
		$data = explode('|',Configuration::get('PS_YM_DATA'));
		if(!empty($data[0]))
		{
			foreach($data as $i => $_array)
			{
				$_list .= PHP_EOL.'<tr>';
				$array = explode(',',$_array);
			
				foreach($array as $k => $v)
				{	
					$_list .= '<td nowrap="nowrap">'.$v.'</td>'.PHP_EOL;
				}
				$_list .= '<td width="25" align="right"><a href="?controller=adminmodules&configure='.$this->name.'&token='.Tools::getValue('token').'&deleteBlockYM&ym_id='.$i.'" onclick="return confirmAction();"><img src="'._PS_IMG_.'admin/disabled.gif" width="16" height="16"></a></td>'.PHP_EOL;
				$_list .= '</tr>'.PHP_EOL;
			}
		}
		

		$_list .= '</table>';
		return $_list;
	}

	private function _displayForm()
	{
		$cb='';
		for($i = 1; $i < 25; $i=$i+1) {
			$cb=$cb.'<option value="'.$i.'">'.$i.'</option>';
		}				
		
		$this->_html .= '
		<style>
			.opi_icon {width:148px; height:164px;float:left;border:1px solid #efefef; position:relative;}
			.opi_icon span {display:block;text-align:center; font-size:1em; border:1px solid red; position:absolute;bottom:0; left:0; width:128px; background-color:#f00;color:#fff;}
		</style>
	<script type="text/javascript" charset="utf-8">
		$(function(){		
			$(\'#dodol\').click(function(){
				$(\'#box\').toggle(\'fast\');
			});
		});
	</script>		
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="margin-bottom:10px;">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Default Block').'</label>
				<div class="margin-form">
					<input type="radio" name="ymblock" id="text_list_on" value="1" '.(Configuration::get('PS_YM_BLOCK')==1 ? 'checked="checked" ' : '').'/>
					<label class="t" for="text_list_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="ymblock" id="text_list_off" value="0" '.(Configuration::get('PS_YM_BLOCK')==0 ? 'checked="checked" ' : '').'/>
					<label class="t" for="text_list_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					<p class="clear">'.$this->l('enable/disable on default block frame').'</p>
				</div>
				<label>'.$this->l('Header Title').'</label>
				<div class="margin-form"><input type="text" name="ymtitle" id="ymtitle" value="'.Configuration::get('PS_YM_TITLE').'" size="50"><sup> *</sup>
					<p class="clear">'.$this->l('title for this block').'</p>
				</div>
				<center><input type="submit" name="btnSetting" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="margin-bottom:10px;">
			<fieldset><legend><img src="../img/admin/add.gif" alt="" title="" />'.$this->l('Add').'</legend>
				<label>'.$this->l('Title').'</label>
				<div class="margin-form"><input type="text" name="ymtitle" id="ymtitle" size="50"><sup> *</sup>
					<p class="clear">'.$this->l('title for your yahoo id').'</p>
				</div>			
				<label>'.$this->l('Yahoo ID').'</label>
				<div class="margin-form"><input type="text" name="ymid" id="ymid" size="50"><sup> *</sup>
					<p class="clear">'.$this->l('insert your yahoo id').'</p>
				</div>
				<label>'.$this->l('Online Icon').'</label>
				<div class="margin-form">
				<select name="ymicon" id="ymicon">'.$cb.'</select>
					<p class="clear">'.$this->l('select your favorite icon').' | <a href="#" id="dodol">Click here to preview the icons</a></p>
				</div>
				<div id="box" style="border:1px solid #ccc; width:100%; display:none; ">				
				<div id="konten" style="background-color:#fff;height:200px;overflow: auto;">
				<div class="opi_icon"><img src="'.$this->_path.'icons/1.gif" /><span>1</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/2.gif" /><span>2</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/3.gif" /><span>3</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/4.gif" /><span>4</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/5.gif" /><span>5</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/6.gif" /><span>6</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/7.gif" /><span>7</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/8.gif" /><span>8</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/9.gif" /><span>9</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/10.gif" /><span>10</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/11.gif" /><span>11</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/12.gif" /><span>12</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/13.gif" /><span>13</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/14.gif" /><span>14</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/15.gif" /><span>15</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/16.gif" /><span>16</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/17.gif" /><span>17</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/18.gif" /><span>18</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/19.gif" /><span>19</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/20.gif" /><span>20</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/21.gif" /><span>21</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/22.gif" /><span>22</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/23.gif" /><span>23</span></div>
				<div class="opi_icon"><img src="'.$this->_path.'icons/24.gif" /><span>24</span></div>
				</div>
				</div>					
				<center><input type="submit" name="btnAdd" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>
		<fieldset><legend><img src="../img/admin/details.gif" alt="" title="" />'.$this->l('List').'</legend>
		    <script type="text/javascript">function confirmAction() { if (typeof(window.opera) != \'undefined\') { return true; } var is_confirmed = confirm(\''.$this->l('Are sure want to delete this data').'\'); return is_confirmed; }</script>
		    '.$this->dataList().'
		</fieldset>
		';
	}

	private function _postValidation()
	{
		if (Tools::isSubmit('btnAdd'))
		{
			if (!Tools::getValue('ymid'))
				$this->_postErrors[] = $this->l('Please Insert Yahoo User-ID');
			elseif (!Tools::getValue('ymtitle'))
				$this->_postErrors[] = $this->l('Title is required');
		}		
		if (Tools::isSubmit('btnSetting'))
		{
			if (!Tools::getValue('ymtitle'))
				$this->_postErrors[] = $this->l('Title is required');
		}		
	}
	
	private function _postProcess()
	{
		if (Tools::isSubmit('btnAdd'))
		{
			$ymid = Tools::getValue('ymid');
			$ymicon = (int)Tools::getValue('ymicon');
		
				
			$_value = Configuration::get('PS_YM_DATA');
			empty($_value) ? $value = Tools::getValue('ymtitle').','.Tools::getValue('ymid').','.Tools::getValue('ymicon') : $value = $_value.'|'.Tools::getValue('ymtitle').','.Tools::getValue('ymid').','.Tools::getValue('ymicon');
			Configuration::updateValue('PS_YM_DATA', $value);
		}
		if (Tools::isSubmit('btnSetting'))
		{
	 		Configuration::updateValue('PS_YM_TITLE', Tools::getValue('ymtitle'));
	 		Configuration::updateValue('PS_YM_BLOCK', Tools::getValue('ymblock'));
		}		
			
		$this->_html .= '<div class="conf confirm"> '.$this->l('Settings updated').'</div>';
	}
	
	public function getContent()
	{
		$this->_html  = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('btnAdd') || Tools::isSubmit('btnSetting'))
		{
			$this->_postValidation();
			if (!count($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors as $err)
					$this->_html .= '<div class="alert error">'.$err.'</div>';
		}
		else if (Tools::isSubmit('deleteBlockYM')){
			$ymid = Tools::getValue('ym_id');
		    $array = explode('|',Configuration::get('PS_YM_DATA'));
		    unset($array[$ymid]);
		    Configuration::updateValue('PS_YM_DATA',implode('|',$array));
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}	
		else
			$this->_html .= '<br />';		

		$this->_displayForm();

		return $this->_html;
	}	

	public function hookDisplayLeftColumn($params)
	{
		global $smarty, $cookie;
		
		if (strlen(Configuration::get('PS_YM_DATA'))>0)
		{
			$_data = explode('|',Configuration::get('PS_YM_DATA'));
			foreach($_data as $data) $array[] = explode(',',$data);
			
			for($i=0;$i<count($_data);$i++)
			{
				$ym[] = array("title" => $array[$i][0],"id" => $array[$i][1],"icon" => $array[$i][2]);
			}
			$smarty->assign('yms', $ym);
		}
		$smarty->assign('title', Configuration::get('PS_YM_TITLE'));
		$smarty->assign('block', Configuration::get('PS_YM_BLOCK'));

		return $this->display(__FILE__, 'blockym.tpl');
	}

	public function hookDisplayRightColumn($params){return $this->hookDisplayLeftColumn($params);}
	
	public function hookDisplayHeader($params){$this->context->controller->addCSS(($this->_path).'blockym.css', 'all');}	
}
?>
