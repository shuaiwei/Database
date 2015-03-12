<?php 
	
	/* Redirect to a different page in the current directory that was requested */
	if(!empty($_GET['customersTable']))
        $tableName = $_GET['customersTable'];
    elseif(!empty($_GET['suppliersTable']))
        $tableName = $_GET['suppliersTable'];
    elseif(!empty($_GET['ordersTable']))
        $tableName = $_GET['ordersTable'];
    elseif(!empty($_GET['shippersTable']))
        $tableName = $_GET['shippersTable'];
    elseif(!empty($_GET['productsTable']))
        $tableName = $_GET['productsTable'];
    elseif(!empty($_GET['employeesTable']))
        $tableName = $_GET['employeesTable'];
    elseif(!empty($_GET['order_detailsTable']))
        $tableName = $_GET['order_detailsTable'];
    elseif(!empty($_GET['categoriesTable']))
        $tableName = $_GET['categoriesTable'];
    else exit("Should not be here");
	
	/* Connect to database and enable "LOAD DATA LOCOL INFILE"*/
	include_once("dbconnect.inc.php"); // let '>' be the last character of the file
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $host, $user, $password, $database);
	if (mysqli_connect_errno()) 
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	// Get column title
	$title = array();
	$query = "SHOW COLUMNS FROM $tableName;";
	if($result = $mysqli->query($query)) 
	{
		while ($row = $result->fetch_assoc())
		{
			$title[] = $row['Field'];
		}
		/* free result set */
		$result->close();
	}
	//Get data
	$allData = '';
	$allData .= implode("\t", $title)."\n";
	$result  = $mysqli->query("SELECT * FROM $tableName");
	while($row = $result->fetch_assoc()) 
	{
	    $allData .= implode("\t", $row)."\n";
	}
	//Remove all the quotes of the downloaded file
	$allData = str_replace("'",'', $allData);
	$allData = str_replace("\"",'', $allData);

	//Select folder in download dialog box to download .txt file
	$fileName = $tableName.".txt";
	header('Content-type: text/plain');
    header("Content-disposition: attachment; filename=$fileName"); 
	echo $allData; 

	mysqli_close($mysqli);

	exit();
?>

<?php
	echo '<br /><a href="downloadData.php download" >Continue to download</a><br>';
?>
<?php
	echo '<br /><a href="index.php">Return to the firstpage</a>';
?>

