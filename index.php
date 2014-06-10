<?php

/**
 * Description of class_google_trans
 * $tl = new class_google_trans("chinese", "en", "zh-CN");
 * echo $tl->translatedText;
 * @author softuses.com
 */
Runtime(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="css/search.css" />
    <title>CreateID</title>
</head>
<body>
	<div id="page">
		<div id="search">
			<form action="index.php" method="post">
				<div id="search-text">
					<input type="search" name="sourceText" maxlength="2048"/>
				</div>
				<button id="search-submit" type="submit">
					<span></span>
				</button>
                <select name="sourceLanguage" value="language">
					<option value="" selected="selected">Auto</option>
					<option value="zh-CN">Chinese</option>
					<option value="en">English</option>
                    <option value="">Other</option>
				</select>				
			</form>
		</div>
		<div id="container">
			<ul>
<?php

class class_google_trans {
    private $translate;
    public static $languages = array(
        //		'ar'	=>'arabic',
        //'bg'	=>'bulgarian',

        //'zh'	=>'chinese',
		'zh-CN'	=>'Chinese_simplified',
		'zh-TW'	=>'Chinese_traditional',
        'ca'	=>'Catalan',
		'hr'	=>'Croatian',
		'cs'	=>'Czech',
		'da'	=>'Danish',
		'nl'	=>'Dutch',
		'en'	=>'English',
		'fi'	=>'Finnish',
		'fr'	=>'French',
		'de'	=>'German',
        //'el'	=>'greek',
        //'iw'	=>'hebrew',
        //'hi'	=>'hindi',
		'id'	=>'Indonesian',
		'it'	=>'Italian',
        //'ja'	=>'japanese',
        //'ko'	=>'korean',
		'lv'	=>'Latvian',
		'lt'	=>'Lithuanian',
		'no'	=>'Norwegian',
		'pl'	=>'Polish',
		'pt-PT'	=>'Portuguese',
		'ro'	=>'Romanian',
        //'ru'	=>'russian',
        //'sr'	=>'serbian',
		'sk'	=>'Slovak',
		'sl'	=>'Slovenian',
		'es'	=>'Spanish',
		'sv'	=>'Swedish',
        //'uk'	=>'ukrainian',
		'vi'	=>'Vietnamese',
        'a'		=>'Auto'
	);
    public $translatedText;
    public $sourceText;
    function __construct($string, $from, $to){
		$this->translate = rawurlencode($string);
		if(strlen($this->translate)==0) $this->translatedText = 'Translation String is Empty.';
        else if(!array_key_exists($from, self::$languages) || !array_key_exists($to, self::$languages)) $this->translatedText = 'Unsupported Language Option.';

		if(strlen($this->translatedText)==0){
                        $url="http://translate.google.com/translate_a/t";
                        $fields=array(
                            'client'=>'t',
                            'text'=>$this->translate,
                            'hl'=>$from,
                            'sl'=>$from,
                            'tl'=>$to,
                            'ie'=>'UTF-8',
                            'oe'=>'UTF-8',
//                            'multires'=>1,
//                            'otf'=>'1',
//                            'pc'=>1,
//                            'ssel'=>1,
//                            'tsel'=>1,
//                            'sc'=>1

                        );
                        $fields_string="";
                        foreach($fields as $key=>$value){
                            $fields_string .= $key.'='.$value.'&';
                        }
                        $fields_string=rtrim($fields_string ,'&');
			$ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST,count($fields));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$body = curl_exec($ch);
			curl_close($ch);
                        $this->getjosn_decode($body);
		}
	}
    /*
   function getjosn_decode($str){
       
       $str=preg_replace('/,{2,}/', ',', $str);
       $json=json_decode($str, true);
       
       var_dump($json);
       
       $translated = '';
        foreach($json[0] as $key => $val){
            $translated .= $val[0];
        }

        $this->translatedText=$this->getrighttext($translated);
   }
   function getrighttext($str){       
       $str1= preg_replace("</\s>", "/", $str);
       $str1= str_replace("> ", ">", $str1);
       $str1= str_replace("' ", "'", $str1);
       $str1= str_replace(" '", "'", $str1);
       $str1= str_replace(" ? ", "?", $str1);
       return $str1;
   }*/
    
    function getjosn_decode($str){
        $str=preg_replace('/,{2,}/', ',', $str);
        $json = json_decode($str, true);
		$translated = '';
        $source = '';
        foreach($json[0] as $val){
            $translated .= $val[0];
            $source .= $val[1];
        }
        $this->translatedText = $translated;
        $this->sourceText = $source;
    }
}


$kv = new SaeKV();
$arr = array('encodekey'=>0);
$kv->set_options($arr);
$ret = $kv->init();


function Runtime($mode=0){
	Static $s;
	IF(!$mode){
		$s=microtime();
		Return;
	}
	$e=microtime();
	$s=Explode(" ", $s);
	$e=Explode(" ", $e);
	Return Sprintf("%.2f ms",($e[1]+$e[0]-$s[1]-$s[0])*1000);
}




if(isset($_GET['s'])){
    $sourceText = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
    $sourceLanguage = filter_var($_GET['l'], FILTER_SANITIZE_STRING);
    if(isset($_GET['u'])){
        $update = 1;
    }
    else{
        $update = 0;
    }
}
else if(isset($_POST['sourceText'])){
    $sourceText = filter_var($_POST['sourceText'], FILTER_SANITIZE_STRING);
    $sourceLanguage = filter_var($_POST['sourceLanguage'], FILTER_SANITIZE_STRING);
    if(isset($_POST['forceUpdate'])){
        $update = 1;
    }
    else{
        $update = 0;
    }      
}
else{
    exit();
}
      


if(!array_key_exists($sourceLanguage, class_google_trans::$languages)){
   $sourceLanguage = 'a';
}

if( ($ret = $kv->get($sourceLanguage."_".$sourceText)) && !($update == 1) )
{
    $result = $ret;
    $kv->set('cached', $kv->get('cached')+1);
}
else
{
    $result[] = array($sourceText, $sourceLanguage);
    foreach(class_google_trans::$languages as $key => $val){
        if($key != $sourceLanguage) {
            try{
            	$tl = new class_google_trans($sourceText, $sourceLanguage, $key);
            }catch(Exception $e){
                $result[] = array('error', $val);
                continue;
            }
            $result[] =  array($tl->translatedText, $val);
        }
    }
    $kv->set($sourceLanguage."_".$sourceText, $result);
    $kv->set('nocached', $kv->get('nocached')+1);
}

unset($result[0]);
foreach($result as $val){
    echo '<li> <span class="language">' . filter_var($val[1], FILTER_SANITIZE_STRING) . '</span> <span class="translate">' . filter_var($val[0] ,FILTER_SANITIZE_STRING) . '</span> </li>';
}

$count = $kv->get('totall');
$kv->set('totall',$count+1);
?>
                			</ul>
		</div>
        
<?php
echo "<p id='footer'>" . Runtime(1) . ". " . $count . " totall, " . $kv->get('cached') . " cached requests." . '<a href="http://sae.sina.com.cn" target="_blank"><img src="http://static.sae.sina.com.cn/image/poweredby/117X12px.gif" title="Powered by Sina App Engine"></a></p>';
?>
	</div>
</body>
</html>
