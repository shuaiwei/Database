<?php
	$inputQuery = $_POST['inputQuery'];
	$inputQuery = strtolower($inputQuery);
	//echo $inputQuery;
	$score = array();
	$titleMap = array();
	$hasOr = false;
	
	if (strpos($inputQuery,' or ') !== false){
		$hasOr = 1;
		$inputQuery = explode('or',$inputQuery);
	}  //??
	else{
		$hasOr = 1;
		$inputQuery = str_replace(" and ", " ", $inputQuery);
		$inputQuery = explode(' ',$inputQuery);
	}

	// remove space in each term;
	$inputQuery = array_map('trim',$inputQuery);

	// get the stem of each word 
	include_once("PorterStemmer.php");
	$inputQuery = array_map('PorterStemmer::Stem',$inputQuery);
	var_dump($inputQuery);

	include_once("dbconnect.inc.php");
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $host, $user, $password, $database);
	if (mysqli_connect_errno()) 
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	foreach($inputQuery as $word){
		$query = "select docID, count, title from invertIndex where Word=\"$word\"";
		if ($result = $mysqli->query($query)){
			while ($row = $result->fetch_assoc()){
				$docID = $row["docID"];
				$count = $row["count"];
				$title = $row["title"];
				if (!isset($titleMap[$docID]))
					$titleMap[$docID] = $title;
				if(isset($score[$docID])){
					if($hasOr !== false)
						$score[$docID]+=$count;
					else
						$score[$docID]=$score[$docID]*$count;
				}
				else{
						$score[$docID] = $count;
				}
			}
			/* free result set */
			$result->close();
		}
	}

	//sorted in reverse alphabetical order
	arsort($score);

	$i = 1;
	echo "<table border=1>";
	echo "<tr><th>index</th><th>title</th><th>Num of Matches</th>";
	foreach($score as $docID=>$singleScore){
		echo "<tr>";
	    echo "<td>".$i."</td><td><a href=\"./docRender.php?docID=$docID\">$titleMap[$docID]</td><td>$singleScore</td>";
	    echo "<tr>";
		$i += 1;
	}

?>