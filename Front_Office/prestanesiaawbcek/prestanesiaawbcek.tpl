<div id="awb_block_right" class="block products_block">
	<h4>{l s='Check Airway Bill' mod='prestanesiaawbcek'}</a></h4>
	<form method="post" action="{$this_path_ssl}">
	<div class="block_contents" style="padding-top:1em;">	
	<div style="clear:both; margin-bottom:1em;">
	<label for="carrier">{l s='Carrier' mod='prestanesiaawbcek'}</label>
	<select id="carrier"><option value="jne">JNE</option></select>
	</div>
	<div style="clear:both; margin-bottom:1em;">
	<label for="txtawb">{l s='AWB No.' mod='prestanesiaawbcek'}</label>
	<input type="text" id="txtawb" name="txtawb" maxlength="25" style="width:100px;" />
	</div>
	</div>
	<input type="submit" name="awb" value="{l s='Check AWB' mod='prestanesiaawbcek'}" class="button_large" />
	</form>
	
</div>