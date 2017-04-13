<?php
error_reporting(E_ERROR);
$dir = $argv[1]?$argv[1]:"test";
$files = listDir($dir);

$sortfile = array();
foreach($files['lists'] as $file){
	$sortfile[$file['name']] = filemtime("$dir/$file[name]");//filemtime
}
arsort($sortfile);
//$sortfile=array_reverse($sortfile);
//var_dump($sortfile);exit;

$i=1;
foreach($sortfile as $k=>$v){
	$p = sprintf("%02d",intval($i/200+1));
	$fp2=@fopen("$dir-$p.html", "w"); 
	echo "$dir-$p.html\n";
	fwrite($fp2,""); 
	fclose($fp2);
	$i++;
}

$i=1;
foreach($sortfile as $k=>$v){
	$p = sprintf("%02d",intval($i/200+1));
	$p2 = sprintf("%02d",intval(($i+1)/200+1));
	$fp2=@fopen("$dir-$p.html", "a"); 
	fwrite($fp2,"<img src='$dir/$k' width=800px>\n"); 
	if($p!=$p2){
		fwrite($fp2,"<a href='$dir-$p2.html'>NEXT</a>\n"); 
	}
	fclose($fp2);
	$i++;
}


//write total index

$htmls = listDir('.');
$sortfile = array();
file_put_contents('index.htm','');
foreach($htmls['lists'] as $file){
	if(preg_match('/\.html$/',$file['name'])){		
		$fp2=@fopen("index.htm", "a"); 
		fwrite($fp2,"<a href='$file[name]'>$file[name]</a> / \n"); 
		fclose($fp2);	
	}
}



function listDir($dir){
    if(!file_exists($dir)||!is_dir($dir)){
        return '';
    }
    $dirList=array('dirNum'=>0,'fileNum'=>0,'lists'=>'');
    $dir=opendir($dir);
    $i=0;
    while($file=readdir($dir)){
        if($file!=='.'&&$file!=='..'){
            $dirList['lists'][$i]['name']=$file;
            if(is_dir($file)){
                $dirList['lists'][$i]['isDir']=true;
                $dirList['dirNum']++;
            }else{
                $dirList['lists'][$i]['isDir']=false;
                $dirList['fileNum']++;
            }
            $i++;
        };
    };
    closedir($dir);
    return $dirList;
}