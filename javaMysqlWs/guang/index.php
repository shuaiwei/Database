<?php
include_once("dbconnect.inc.php");
include_once("PorterStemmer.php");
if($_SERVER['REQUEST_METHOD'] =='POST'){
	$input = $_POST['keywords'];
	$input = strtolower($input);
	//echo $input;
	$score = array();
	$title_map = array();
	$or_flag = false;
	if (strpos($input,' or ')!=false){
		$or_flag = true;
		$keywords = explode('or',$input);
	}else{
		$or_flag = false;
		str_replace(" and "," ",$input);
		$keywords = explode(' ',$input);
	}
	//print_r($keywords);
	$keywords = array_map('trim',$keywords);
//	print_r($keywords);
	$keywords = array_map('PorterStemmer::Stem',$keywords);
//	print_r($keywords);

	foreach($keywords as $kw){
		$query1 = "select DocID, Count, Title from invert_index where Word=\"$kw\"";
		//echo $query1;
		$result1 = mysql_query($query1);
		while($row = mysql_fetch_array($result1)){
			$DocID = $row["DocID"];
			$count = $row["Count"];
			$title = $row["Title"];
			if (!isset($title_map[$DocID]))
				$title_map[$DocID] = $title;
			if(isset($score[$DocID])){
				if($or_flag)
					$score[$DocID]+=$count;
				else
					$score[$DocID]=$score[$DocID]*$count;
			}else{
					$score[$DocID] = $count;
			}
		}
	}
	arsort($score);
	$i = 1;
	echo "<table border=1>";
	echo "<tr><th>Index</th><th>Title</th><th>Num of Matches</th>";
	foreach($score as $DocID=>$tf){
		echo "<tr>";
        echo "<td>".$i."</td><td><a href=\"./DocRender.php?DocID=$DocID\">$title_map[$DocID]</td><td>$tf</td>";
        echo "<tr>";
		$i += 1;
	}

}


?>

<html>
   <head>
      <title>Simple Search Engine</title>
   </head>
   <body>
      <form enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF'] ?>" method="POST">
          Input your query here:<input type="text" value="" name="keywords">
         <input type="submit" name="submit" value="search" />
      </form>
   </body>
</html>

