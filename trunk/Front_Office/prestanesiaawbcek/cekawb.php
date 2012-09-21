<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include_once(dirname(__FILE__).'/prestanesiaawbcek.php');

$form = new PrestanesiaAWBCek();
echo $form->cekAWB();

include_once(dirname(__FILE__).'/../../footer.php');