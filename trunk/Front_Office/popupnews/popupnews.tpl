{if $page_name=='index' OR  $show_all=='1'}
<script type="text/javascript" src="{$this_path}jquery.meerkat.1.3.min.js"></script>
<link href="{$this_path}splash.css" rel="stylesheet" type="text/css" />
</div><!--footer-->
</div><!--page-->
<div class="meerkat"> 
	<div id="splash"> 
		<div id="splash-content">
			<h1>{$newstitle}</h1>
			{$news}
		</div>
		<div id="splash-footer"><a href="#" id="dont-show">{l s='Dont show this window again' mod='popupnews'}</a> | <a href="#" id="close">{l s='Close' mod='popupnews'}</a></div>
	</div> 
</div> 
<script type="text/javascript" >
{literal}
	$(function(){$('.meerkat').meerkat({background: '#333',opacity: '0.75',height: '100%',width: '100%',position: 'top',dontShowAgain: '#dont-show',close: '#close',animationIn: 'fade',animationOut: 'fade',animationSpeed: 500,timer: {/literal}{$interval}{literal}});});
{/literal}		
</script>
{/if}