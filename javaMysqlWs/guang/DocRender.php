<?php
	if(isset($_GET['DocID'])){
		$DocID = $_GET['DocID'];
		$file = "./files/".$DocID.".txt";
		$content = file_get_contents($file);
		$content = explode("\n",$content);
		foreach($content as $line){
			echo $line;
			echo "</br>";
		}
	}
?>