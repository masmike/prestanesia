<div id="awb_block_right" class="block products_block">
	<h4>{l s='Supported Carrier' mod='prestanesiaourcarrier'}</a></h4>
	<div class="block_content" style="padding-top:1em;">
	{if isset($carriers)}
		{foreach from=$carriers item=carrier name=carriers}
			<p><img src="{$base_dir}img/s/{$carrier.id_carrier}.jpg" alt="{$carrier.name}" /></p>
		{/foreach}
	{/if}		
	</div>
</div>