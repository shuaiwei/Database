<?php
$content = file_get_contents("./tempFile");
$content = str_replace("\""," ",$content);

$keyStart = strpos($content,'<U>')+3;
//Returns the portion of string specified by the start and length parameters.
$keyUnique = substr($content,$keyStart,8);

$titleStart = strpos($content,'<T>')+3;
$titleEnd = strpos($content,'</T>')-2;
$titleLength = $titleEnd - $titleStart;
$title = substr($content,$titleStart,$titleLength);

//when no result found, return false
if (strpos($content,'<W>') !== false){
	$abstractStart = strpos($content,'<W>')+3;
	$abstractEnd = strpos($content,'</W>')-2;
	$abstractLength = $abstractEnd - $abstractStart;
	$abstract = substr($content,$abstractStart,$abstractLength);
}

if (strpos($content,'<M>') !== false){
	$meshTermStart = strpos($content,'<M>')+3;
	$meshTermEnd = strpos($content,'</M>')-2;
	$meshTermLength = $meshTermEnd - $meshTermStart;
	$meshTerm = substr($content,$meshTermStart,$meshTermLength);
}

//remove one tag like  <U>
$pattern = '/<{1}[a-zA-Z]{1}>{1}/';
$content = preg_replace($pattern, '', $content);

//remove one tag like one </U>
$pattern = '`<{1}/{1}[a-zA-Z]{1}>{1}`';
$content = preg_replace($pattern, '', $content);
//there are some differences in regex match between preg_replace and str_replace

$file = "./classifiedDocuments/".$keyUnique.".txt";
$file = fopen($file,'w');
fwrite($file,$content);
fclose($file);

//remove one or more character other than 0-9,a-z and A-Z
$pattern = '/[^a-zA-Z0-9]+/';
$words = preg_replace($pattern, ' ', $title); //need variable word
$words = strtolower($words);
$words = explode(' ',$words);
//similar to popup in stack: remove the "" in the last place
array_pop($words);

if (isset($abstract)){
	$abstract = preg_replace($pattern, ' ', $abstract);
	$abstract = strtolower($abstract);
	$abstract = explode(' ',$abstract);
	array_pop($abstract);

	$words = array_merge($words, $abstract);
}

if (isset($meshTerm)){
	$meshTerm = preg_replace($pattern, ' ', $meshTerm);
	$meshTerm = strtolower($meshTerm);
	$meshTerm = explode(' ',$meshTerm);
	array_pop($meshTerm);

	$words = array_merge($words, $meshTerm);
}

$words = array_map('trim',$words);

$fileStopWords = "./stopwords.txt";
$stopWords = explode("\n", file_get_contents("$fileStopWords"));
$stopWords = array_map('trim',$stopWords);

//return the values in array1 that are not present in any of the other arrays.
$words = array_merge(array_diff($words,$stopWords));

include_once("PorterStemmer.php");
$stemmer = new PorterStemmer;
$stemedwords = array_map('PorterStemmer::Stem',$words);

$wordsArray = array();  //key->value: word->count
foreach($stemedwords as $wds){
	//var_dump($w);
	if (isset($wordsArray[$wds])){
		$wordsArray[$wds] += 1;
	}
	else{
		$wordsArray[$wds] = 1;
	}
}

$i = 1000;  //$keyUnique and $i as index ID

$sqlFile = "init.sql";
$sqlFile = fopen($sqlFile,'a') or die("cannot open file");
foreach($wordsArray as $word=>$count){
	$indexID = $keyUnique.$i;
	$query = "insert into invertIndex(indexID,word,docID,count,title) values($indexID,\"$word\",\"$keyUnique\",$count,\"$title\");";
	fwrite($sqlFile,$query);
	fwrite($sqlFile,"\n");
	$i += 1;
}
fclose($sqlFile);
?>
