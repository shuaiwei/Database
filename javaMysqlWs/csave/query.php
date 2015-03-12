<?php
	$inputQuery = $_POST['inputQuery'];
	$inputQuery = strtolower($inputQuery);
	//echo $inputQuery;
	$score = array();
	$titleMap = array();
	$hasOr = false;  //default false
	$hasAnd = false;  //default false
	if ((strpos($inputQuery,' or ') !== false) && (strpos($inputQuery,' and ') !== false)) {
		$hasOr = 1;
		$hasAnd = 1;
		$inputQuery = str_replace(" and ", " ", $inputQuery);
		$inputQuery = str_replace('(', '', $inputQuery);
		$inputQuery = str_replace(')', '', $inputQuery);
		$inputQuery = explode(' or ',$inputQuery);

		$string1 = explode(' ', $inputQuery[1]);
		$string0 = explode(' ', $inputQuery[0]);
		//seperate expression
		if(count($string0)>1){
			for($i=count($string0)-2; $i>=0; $i--){
				$inputQuery[1] = $string0[$i]." ".$inputQuery[1];
			}
		}
		if(count($string1)>1){
			for($i=1; $i<count($string1); $i++){
				$inputQuery[0] = $inputQuery[0]." ".$string1[$i];
			}
		}
		$inputQuery[0] = explode(' ', $inputQuery[0]);
		$inputQuery[1] = explode(' ', $inputQuery[1]);
		$inputQuery[0] = array_map('trim',$inputQuery[0]);
		$inputQuery[1] = array_map('trim',$inputQuery[1]);

	}
	elseif ((strpos($inputQuery,' or ') !== false) && (strpos($inputQuery,' and ') === false)){
		$hasOr = 1;
		$inputQuery = str_replace('(', '', $inputQuery);
		$inputQuery = str_replace(')', '', $inputQuery);

		$inputQuery = explode('or',$inputQuery);

		//just one or, then $inputQuery[0] & $inputQuery[1] be a string
		$inputQuery = array_map('trim',$inputQuery);

	}  
	elseif ((strpos($inputQuery,' or ') === false) && (strpos($inputQuery,' and ') !== false)){
		$hasAnd = 1;
		$inputQuery = explode('and',$inputQuery);
		$inputQuery = array_map('trim',$inputQuery);

	}

	else{
		$inputQuery = explode(' ',$inputQuery);
			$inputQuery = array_map('trim',$inputQuery);

	}

	//var_dump($inputQuery);
	// remove space in each term;

	// get the stem of each word 
	include_once("PorterStemmer.php");
		
		if(($hasOr !== false) && ($hasAnd !==false)){
			$inputQuery[0] = array_map('PorterStemmer::Stem',$inputQuery[0]);
			$inputQuery[1] = array_map('PorterStemmer::Stem',$inputQuery[1]);
		}
		else 
			$inputQuery = array_map('PorterStemmer::Stem',$inputQuery);

	include_once("dbconnect.inc.php");
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $host, $user, $password, $database);
	if (mysqli_connect_errno()) 
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	//get docID from two expression
	if(($hasOr !== false) && ($hasAnd !==false)){
		$docID = array();
		foreach($inputQuery[0] as $word){
			echo "$word\n";
			$query = "select docID from invertIndex where word=\"$word\"";
			if ($result = $mysqli->query($query)){
				while ($row = $result->fetch_assoc()){
					$docID[$word][] = $row["docID"];
				}
				$result->close();
			}
		}
		$result0 = call_user_func_array('array_intersect',$docID);

		$docID = array();
		foreach($inputQuery[1] as $word){
			echo "$word\n";
			$query = "select docID from invertIndex where word=\"$word\"";
			if ($result = $mysqli->query($query)){
				while ($row = $result->fetch_assoc()){
					$docID[$word][] = $row["docID"];
				}
				$result->close();
			}
		}
		$result1 = call_user_func_array('array_intersect',$docID);
		$docID = array_merge($result0,$result1);
		$docID = array_unique($docID);
		var_dump($docID);
	}
	else{
		$docID = array();
		foreach($inputQuery as $word){
			echo "$word\n";
			$query = "select docID from invertIndex where word=\"$word\"";
			if ($result = $mysqli->query($query)){
				while ($row = $result->fetch_assoc()){
					$docID[$word][] = $row["docID"];
				}
				$result->close();
			}	
		}
		$result = call_user_func_array('array_intersect',$docID);
		$docID= $result;
		var_dump($docID);
	}
	var_dump($docID[0]);
	if(($hasOr !== false) && ($hasAnd !==false)){
		for($i=0; $i<2; $i++){
			foreach($inputQuery[$i] as $word){
				for($j=0; $j<count($docID); $j++){
					$query = "select docID, count, title from invertIndex where word=\"$word\"
								and docID = $docID[$j]";
					if ($result = $mysqli->query($query)){
						while ($row = $result->fetch_assoc()){
							$docID = $row["docID"];
							$count = $row["count"];
							$title = $row["title"];
							if (!isset($titleMap[$docID]))
								$titleMap[$docID] = $title;  //save title
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
			}
		}
	}
	//echo $query;
	else{
		foreach($inputQuery as $word){
			for($j=0; $j<count($docID); $j++){
				$query = "select docID, count, title from invertIndex where word=\"$word\"
									and docID = $docID[$j]";
				if ($result = $mysqli->query($query)){
					while ($row = $result->fetch_assoc()){
						$docID = $row["docID"];
						$count = $row["count"];
						$title = $row["title"];
						if (!isset($titleMap[$docID]))
							$titleMap[$docID] = $title;  //save title
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
		}
	}

	//sorted in reverse alphabetical order
	arsort($score);

	$i = 1;
	echo "<table border=1>";
	echo "<tr><th>index</th><th>title</th><th>Num of Matches</th>";
	foreach($score as $docID=>$singleScore){
		echo "<tr>";
	    echo "<td>".$i."</td>
	    	  <td><a href=\"./docRender.php?docID=$docID\">$titleMap[$docID]</td>
	    	  <td>$singleScore</td>";
	    echo "<tr>";
		$i += 1;
	}

?>