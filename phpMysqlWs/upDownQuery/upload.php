<?php session_start();
	  
	$tableName = $_SESSION['tableName'];

	if(empty($_FILES['userfile']['tmp_name'])) 
	{
		echo '<br /><a href="index.php">Return</a><br />';
		exit("You should select a file to upload, exit.");
	}

	/* Put the uploaded file into the directory /home/shuaiwei/Documents/phpshuaiwei/
		and rename it as temp.tmp";                                             */
	include_once("dbconnect.inc.php");
	if (is_uploaded_file($_FILES['userfile']['tmp_name']))
	{
	        move_uploaded_file($_FILES['userfile']['tmp_name'],$FileDestination);
	        print "The file " . $_FILES['userfile']['name'] . " has been uploaded successfully.<br>";
	}
	else print "Possible file upload attack. Filename: " . $_FILES['userfile']['tmp_name'];

	//Remove all the quotes of the uploaded file
	$fileData = file_get_contents($FileDestination,true);
	$fileData = str_replace("'",'', $fileData);
	$fileData = str_replace("\"",'', $fileData);
	file_put_contents($FileDestination, $fileData);

	//Remove blank line & Get the first line of the FileDestination 
	file_put_contents($FileDestination, implode(PHP_EOL, file($FileDestination, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
	$f = fopen($FileDestination, 'r');
	$firstline = fgets($f);
	fclose($f);

	// Put all the column headings into $keywords
	$keywords = preg_split("@\t@", $firstline);

	// Put the number of column headings into $countsOfAttributes 
	$countsOfAttributes = count($keywords);

	// Connect to database and enable "LOAD DATA LOCOL INFILE"
	include_once("dbconnect.inc.php");
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $host, $user, $password, $database);
	if (mysqli_connect_errno()) 
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	

	//Stop updating nonunique indexes
	$query = "ALTER TABLE $tableName DISABLE KEYS;";	
	$mysqli->query($query) or die($mysqli->error.__LINE__);

	// Load and update data
	$commaSeparatedkeywords = implode(",", $keywords);	
	$query ="LOAD DATA LOCAL INFILE '$FileDestination' REPLACE into TABLE $tableName Columns terminated by '\t' LINES TERMINATED BY '\n' IGNORE 1 lines ($commaSeparatedkeywords);";
	$mysqli->query($query) or die($mysqli->error.__LINE__);

	//Re-create missing indexes
	$query = "ALTER TABLE $tableName ENABLE KEYS;";	
	$mysqli->query($query) or die($mysqli->error.__LINE__);

	//Replace empty string with NULL
	/*for($i = 0; $i < $countsOfAttributes; $i = $i + 1){
		$query = "UPDATE $tableName SET $keywords[$i] = NULL WHERE $keywords[$i] = '';";
		$mysqli->query($query) or die($mysqli->error.__LINE__);
	}*/

	mysqli_close($mysqli);		

	//When we insert a blank line carelessly, only by creating table again, \
	//can we remove the record with NULL primary key since updating can't change it!
	echo "Data have been uploaded to table $tableName successfully"
?>

<?php
		echo '<br /><a href="nightlyProcess.php">Continue to upload</a><br>';
?>
<?php
		echo '<br /><a href="index.php">Return to the firstpage</a>';
?>

