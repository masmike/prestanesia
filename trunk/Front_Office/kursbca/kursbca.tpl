<!-- begin bca widget by prestanesia.com -->
<style type="text/css">@import url({$this_path}kursbca.css);</style>
<div id="kursbca_block_left" class="block">
	<h4>{l s='Kurs'}</h4>
	<div class="block_content">
	<div id="kurs"></div>
	</div>
</div>
<script type="text/javascript">
{literal}
$(document).ready(function($){$.getJSON("{/literal}{$this_path}{literal}bca.php",function(data){var x=1;var out='<div class="head-1">Mata Uang</div><div class="head-2">Jual</div><div class="head-3">Beli</div>';$.each(data,function(i){out+='<div class="col-'+x+'">'+data[i]+'</div>';if(x==3)x=0;x+=1;});out+='<div style="clear:both"></div><p style="text-align:right;">Sumber : <a href="http://www.klikbca.com" title="Bank BCA" target="_blank">BCA</a><br/>Modul oleh <a href="http://prestanesia.com" title="Prestanesia - Solusi Toko Online Anda" target="_blank">Prestanesia</a></p>';$('#kurs').html(out);});})
{/literal}
</script>
<!-- end bca widget -->