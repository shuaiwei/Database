<?php 
	session_start();
	/* Save input data */
	$saveInput = array();
	if(!empty($_POST['StudentID']))
		$saveInput["STUDENT_ID"] = $_POST["StudentID"];
	if(!empty($_POST['GPA']))
		$saveInput["GPA"] = $_POST["GPA"];
	if(!empty($_POST['ACT']))
		$saveInput["ACT"] = $_POST["ACT"];
	if(!empty($_POST['CountyName']))
		$saveInput["COUNTY_NAME"] = $_POST["CountyName"];
	if(!empty($_POST['Gender']))
		$saveInput["GENDER"] = $_POST['Gender'];
	if(!empty($_POST['StateOrigin']))
		$saveInput["STATE_ORIGIN"] = $_POST['StateOrigin'];
	if(!empty($_POST['Major']))
		$saveInput["MAJOR"] = $_POST['Major'];

	/* combine a query to select responding records from database*/
	$flag = 0;
	$query = array();
	if (array_key_exists('STUDENT_ID', $saveInput)) 
	{	
		$query[] = "STUDENT_ID= $saveInput[STUDENT_ID]";
		$flag = $flag + 1;		
	}
	if (array_key_exists('GPA', $saveInput)) 
	{	
		if($flag != 0)
			$query[] = "AND GPA= $saveInput[GPA]";
		else $query[] = "GPA= $saveInput[GPA]";
		$flag = $flag + 1;		
	}	
	if (array_key_exists('ACT', $saveInput)) 
	{	
		if($flag != 0)
			$query[] = "AND ACT= $saveInput[ACT]";
		else $query[] = "ACT= $saveInput[ACT]";	
		$flag = $flag + 1;
	}
	if (array_key_exists('COUNTY_NAME', $saveInput)) 
	{	
		if($flag != 0)
			$query[] = "AND COUNTY_NAME LIKE \"%$saveInput[COUNTY_NAME]%\"";
		else $query[] = "COUNTY_NAME LIKE \"%$saveInput[COUNTY_NAME]%\"";	
		$flag = $flag + 1;
	}
	if (array_key_exists('GENDER', $saveInput)) 
	{	
		if($flag != 0)
			$query[] = "AND GENDER= '$saveInput[GENDER]'";
		else $query[] = "GENDER= '$saveInput[GENDER]'";	
		$flag = $flag + 1;
	}
	if (array_key_exists('STATE_ORIGIN', $saveInput)) 
	{	
		if($flag != 0)
			$query[] = "AND STATE_ORIGIN= '$saveInput[STATE_ORIGIN]'";
		else $query[] = "STATE_ORIGIN= '$saveInput[STATE_ORIGIN]'";	
		$flag = $flag + 1;
	}
	if (array_key_exists('MAJOR', $saveInput)) 
	{	
		if($flag != 0)
			$query[] = "AND MAJOR= '$saveInput[MAJOR]'";
		else $query[] = "MAJOR= '$saveInput[MAJOR]'";	
	}
	$query = implode(" ", $query);

	/* Select records according to a user's input" */
		/*Decide whether there is data */
	if(empty($query))
		$query = "SELECT * FROM Applications";
    else $query = "SELECT * FROM Applications WHERE $query;";

	/* Connect to database */
	include_once("dbconnect.inc.php");
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $host, $user, $password, $database);
	if (mysqli_connect_errno()) 
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* search records */
	$serachResults = array();
	$row = array(); 
    if ($result = $mysqli->query($query)) 
	{
		while ($row[] = $result->fetch_assoc());
		$record = array_filter($row);
		$result->close();
	}
	else echo "No results found.<br>";

	//Have results, but record is null;
	if(empty($record)&&($result)) 
		echo "No results found.<br>";
	
	if(!empty($record))
	{
		// Get the column title of table Applications */
		$query = "SHOW COLUMNS FROM Applications;";
		$column = array();
		if ($result = $mysqli->query($query)) 
		{
			while ($row = $result->fetch_assoc())
			{
				$column[] = $row['Field'];
			}
			/* free result set */
			$result->close();
		}
		mysqli_close($mysqli);
	}
?>	

<?php if(!empty($record)):?>
	<!-- Show results in a table in my website-->
	<style type="text/css">

	.tftable th {background-color:#0000FF;font-color="red"}
	.tftable tr {background-color:#FFFFFF;}
	.tftable tr:hover a {color: #FF0000;}
	</style>
	<table class="tftable" border="5">
	<tr> <?php foreach ($column as $column): ?>
		<th bgcolor="#FF0000"><font color="#FFFFFF"><?php echo $column; ?></th>
		<?php endforeach; ?>
	</tr>
	<?php foreach ($record as $values): ?>
	<tr>
		<?php foreach ($values as $value): ?>
		<td bgcolor="#FFFFFF"><font color="#000000"><?php echo $value; ?></td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
	</table>
<?php endif;?>

<?php
	if(!empty($record))
	{
		$count = count($record);
		if($count == 1)
			echo "$count record was found.<br>";
		else echo "$count records were found.<br>";
	}
?>

<form id="return" action="index.php" method="post">
<input type="submit" name="submit" value="Return to search!" />
</form>



