<?php
error_reporting(E_ERROR);
$dir = $argv[1]?$argv[1]:"test";
$files = listDir($dir);

$sortfile = array();
foreach($files['lists'] as $file){
	$sortfile[$file['name']] = filemtime("$dir/$file[name]");//filemtime
}
//arsort($sortfile);
//$sortfile=array_reverse($sortfile);
//var_dump($sortfile);exit;


$i=0;
file_put_contents("$dir-auto.html","");
$fp2=@fopen("$dir-auto.html", "a");
fwrite($fp2,'<input type="text" id="index" /> <input type="button" id="go" value="pre" onclick="pre2()"/> <input type="button" id="go" value="go" onclick="go()"/> <input type="button" id="go" value="next" onclick="next()"/> <input type="button" id="go" value="stop" onclick="setloop()"/><br><img id="clock" style="max-width:900px" height="1000px" /><img id="clock2"  height="1000px" style="max-width:900px"/><script language=javascript>
var int=self.setInterval("clockinterval()",6000)
	var index=0;
var loop=true;
var ignore = 0;
function setloop()
  {
	loop = !loop;
  }

function go()
  {

  index=parseInt(document.getElementById("index").value);
clock();
  }
function pre2()
  {
ignore=1;
  index=index-4;
  document.getElementById("index").value=index;
clock();
  }
  function next()
  {
ignore=1;
  //index=index+2;
  document.getElementById("index").value=index;
clock();
  }
 function clockinterval()
  {
	  if(ignore>0){
		  ignore=ignore-1;
		  return;
	  }
  	  if(!loop){return;}
  	  clock();
  }
  
function clock()
  {  	  
  var t=new Date()
  document.getElementById("clock").src=mycars[index];
  index=index+1;
  document.getElementById("index").value=index;
  document.getElementById("clock2").src=mycars[index];
  index=index+1;
  document.getElementById("index").value=index;
  }
  var mycars=new Array();'); 

foreach($sortfile as $k=>$v){
	fwrite($fp2,"mycars[$i]='$dir/$k';\n"); 
	$i++;
}


fwrite($fp2,'</script>');
fclose($fp2);

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