<?php
require('utils.php');


getimages("http://xxxx.com/_%d.shtml",1,4);//198);
function getimages($base_url,$start,$end){
	for($i=$start;$i<=$end;$i++){
		echo "=============== $i =================\n";
		$webpage = file_get_contents(sprintf($base_url,$i));
		preg_match_all('/<td bgcolor=\'#FFFFFF\'><a href=([^ ]+) target=_blank>/',$webpage,$links);        
		foreach($links[1] as $url){		 
            echo $url;
            $str = file_get_contents("http://www.77tuba.com".$url);
            preg_match_all('/<img src=\'([^ ]+)\'/',$str,$matches);                
            foreach($matches[1] as $image){                
                tryntime($image,md5($image).'_'.basename($image),"pic1",2);
            }                
		}
	}
}

