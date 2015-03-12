<?php
include_once("PorterStemmer.php");
//$content = $argv[1];
$content = file_get_contents("./temp");
/* $parser = xml_parser_create();
function start($parser,$element_name,$element_attrs){
   echo $element_attrs;
}
function stop($parser,$element_name){
   echo "haha";
}
xml_set_element_handler($parser,"start","stop");
xml_set_character_data_handler($parser,"char");

xml_parse($parser,$content); */
$key_s = strpos($content,'<U>')+3;
$key_e = strpos($content, '</U>');
$key = substr($content,$key_s,8);
//echo $key;
$title_s = strpos($content,'<T>')+3;
$title_e = strpos($content,'</T>')-2;
$title_len = $title_e - $title_s;
$title = substr($content,$title_s,$title_len);
$title = str_replace("\""," ",$title);
//echo $title;

if (strpos($content,'<W>')!=False){
	$abs_s = strpos($content,'<W>')+3;
	$abs_e = strpos($content,'</W>')-2;
	$abs_len = $abs_e - $abs_s;
	$abs = substr($content,$abs_s,$abs_len);
//	echo $abs;
}
echo "\n";

$pattern = '/<{1}[a-zA-Z0-9]{1}>{1}/';
//echo preg_match($pattern,$content,$matches);
//print_r($matches);
$content = preg_replace($pattern, '', $content);
$pattern = '`<{1}/{1}[a-zA-Z0-9]{1}>{1}`';
$content = preg_replace($pattern, '', $content);
//echo $content;

if(!is_dir("./files"))
	mkdir("./files");
$file = "./files/".$key.".txt";
$fh = fopen($file,'w');
fwrite($fh,$content);
fclose($fh);

$pattern = '/[^a-zA-Z0-9]+/';
$words = preg_replace($pattern, ' ', $title);
//echo $words;
$words = strtolower($words);
$words = explode(' ',$words);
array_pop($words);
//print_r($words);

if (isset($abs)){
	$abs = preg_replace($pattern, ' ',$abs);
	$abs = strtolower($abs);
//	echo $abs;
	$abs = explode(' ',$abs);
	array_pop($abs);
	$words = array_merge((array)$words,(array)$abs);
}
//print_r($words);

$words = array_map('trim',$words);

$f_sw = "./stopwords.txt";
$stop_words = explode("\n", file_get_contents("$f_sw"));
$stop_words = array_map('trim',$stop_words);
//print_r($stop_words);
$words = array_merge(array_diff($words,$stop_words));
//print_r($words);

$stemmer = new PorterStemmer;
$stemedwords = array_map('PorterStemmer::Stem',$words);
//print_r($stemedwords);

$tf = array();
foreach($stemedwords as $w){
	if (isset($tf[$w])){
		$tf[$w] += 1;
	}else{
		$tf[$w] = 1;
	}
}
print_r($tf);

$i = 1094;

$sqlFile = "init.sql";
$sql_fh = fopen($sqlFile,'a') or die("cannot open file");
foreach($tf as $word=>$count){
	$indexID = $key.$i;
	$query = "insert into invert_index(IndexID,Word,DocID,Count,Title) values($indexID,\"$word\",\"$key\",$count,\"$title\");";
	fwrite($sql_fh,$query);
	fwrite($sql_fh,"\n");
	$i+=1;
}
fclose($sql_fh);
?>
