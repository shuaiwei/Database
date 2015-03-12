<?php
	if(isset($_GET['docID'])){
		$docID = $_GET['docID'];
		$file = "./classifiedDocuments/".$docID.".txt";
		$content = file_get_contents($file);
		$content = explode("\n",$content);
		foreach($content as $line){
			echo $line;
			echo "</br>";
		}
	}
?>