<?php
error_reporting(E_ERROR);
ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50728;)'); 



function savefile($a,$b,$mode='w'){
 $fp2=@fopen("$a", $mode); 
 fwrite($fp2,$b); 
 fclose($fp2);
}

function wget($remote_server, $post_string="")
    {
        $context = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded' .
                    '\r\n' . 'User-Agent : Jimmy\'s POST Example beta' .
                    '\r\n' . 'Content-length:' . strlen($post_string) + 8,
                'content' => 'mypost=' . $post_string)
        );
        $stream_context = stream_context_create($context);
        $data = file_get_contents($remote_server, false, $stream_context);
        return $data;
    }

function getHttpsData($url,$timeout=30){
    $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;  
    $ch = curl_init();  
    $opt = array(  
            CURLOPT_URL     => $url,  
            CURLOPT_HEADER  => 0,             
            CURLOPT_RETURNTRANSFER  => 1,  
            CURLOPT_TIMEOUT         => $timeout,  
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => 1,  
            );  
    if ($ssl)  
    {  
        $opt[CURLOPT_SSL_VERIFYHOST] = 1;  
        $opt[CURLOPT_SSL_VERIFYPEER] = FALSE;  
    }  
    curl_setopt_array($ch, $opt);  
    $data = curl_exec($ch);  
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);  
    return array('code'=>$httpcode,'data'=>$data);
}
function tryntime($url,$file,$dir,$times=100){
    echo "\nget $url\n";
    $timeout=100;
    $a=null;
    for($j=1;$j<$times+1;$j++){
        $a = GrabImageHttps($url,$file,$dir,$timeout); 
        echo "try:$j code:$a[code]\n";
        if($a['code']=="404" || $a['code']=="302" || $a['code']=="200"){break;}  
        $timeout+=50;
    }
    return $a;
}
function GrabImageHttps($url,$filename,$dir='pic1',$timeout=100) {
    if(file_exists("$dir/$filename") && filesize("$dir/$filename") !=0 ){echo "$dir/$filename exists\n";return array('code'=>200);}
    //echo "get $url\n";
    $img_url=$url;
    $save_path =$dir;   
    if (trim($img_url) == '') {
        return array('code'=>801);
    }
    if (trim($save_path) == '') {
        $save_path = './';
    }

    //创建保存目录
    if (!file_exists($save_path) && !mkdir($save_path, 0777, true)) {
        return array('code'=>802);
    }
    // curl下载文件
    $data = getHttpsData($url,$timeout);
    echo "CODE:".$data['code']."\n";
    if(substr($data['code'],0,1)!='2'){
        //return $data;
    }
    $data['code']=200;
    // 保存文件到制定路径
    //echo $data['data'];
    if($filename && $data['data']){
        file_put_contents($save_path."/".$filename, $data['data']);
        echo "saved $save_path/$filename size:".(filesize($save_path."/".$filename)/1000)."K\n";
    }else{
        return array('code'=>803);
    }

    return $data;
}
function GrabImage($url,$filename,$dir='pic1') { 
 if(file_exists("$dir/$filename")){return;}echo "get $url\n";
 if($url==""):return false;endif; 
 
 ob_start(); 
 readfile($url); 
 $img = ob_get_contents(); 
 ob_end_clean(); 
 $size = strlen($img); 
 
 //"../../images/books/"为存储目录，$filename为文件名
 $fp2=@fopen("$dir/".$filename, "w"); 
 fwrite($fp2,$img); 
 fclose($fp2); 
 echo "saved $dir/$filename\n\n";
 
 return $filename; 
} 