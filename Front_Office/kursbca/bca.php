<?php

/////////////////////////////////////////// 
// 
// BCA 
// version 0.1 
// By Danni Afasyah
// afasyah [at] gmail [dot] com 
// http://prestanesia.com
// 
// 
// Contoh : 
// 		$bca= new bca(); 
//  	echo $bca->getKurs();
// Output (JSON) :	
// 		["USD"," 9080.00"," 8930.00","SGD"," 6719.55"," 6584.55","HKD"," 1168.50"," 1147.20","CHF"," 8953.90"," 8779.90"]
// 
// 
/////////////////////////////////////////// 

$bca= new bca(); 
echo $bca->getKurs();

class bca{ 
	var $regex='#\<td\salign=".*"\sclass="kurs"\sbgcolor[^>]*>(.*)\<\/td>#'; 
    var $url="http://www.klikbca.com";

    function bca(){} 

	function getKurs(){
		$data = $this->scrape($this->url);
	
		preg_match_all($this->regex,$data,$match);

		//var_dump($match); 
		$json=json_encode($match[1]);		
		return $json;
	}
	
	function scrape($url){ 
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;	
	}
	
}

?>