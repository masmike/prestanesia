{if $block == 1}<div id="ymblock_left" class="block">
    <h4>{if $title}{$title}{else}Block YM+{/if}</h4>
    <div class="block_content ymblock">
{/if}
{if isset($yms)}
    {foreach from=$yms item=ym}
	<h3>{$ym.title}</h3>
    <a href="ymsgr:sendIM?{$ym.id}"><img src="http://mail.opi.yahoo.com/online?u={$ym.id}&m=g&t={$ym.icon}" title="{$ym.id} YM! Status" style="margin-bottom:1em;" ></a>
    {/foreach}
{/if}
{if $block == 1}</div></div>{/if}