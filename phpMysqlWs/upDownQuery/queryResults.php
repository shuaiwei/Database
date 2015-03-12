<?php 
	session_start();
	/* Save input data */
	$saveInput = array();
	if(!empty($_POST['SqlQuery']))
		$query = $_POST["SqlQuery"];

	echo "$query<br>";

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
		//Get table name seleced
		$query = explode(' ', $query);
		$tableNmae = str_replace(';', '', $query[3]);

		// Get the column title of table Applications */
		if($query[1]=="*")
		{
			$query = "SHOW COLUMNS FROM $tableNmae;";
			$column = array();
			if($result = $mysqli->query($query)) 
			{
				while ($row = $result->fetch_assoc())
				{
					$column[] = $row['Field'];
				}
				/* free result set */
				$result->close();
			}
		}
		else $column = explode(',', $query[1]);
	}
	mysqli_close($mysqli);
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
			echo "$count record was found.<br><br>";
		else echo "$count records were found.<br><br>";
	}
?>

<?php
	echo '<br /><a href="sqlQuery.php">Continue to query</a><br>';
?>
<?php
	echo '<br /><a href="index.php">Return to the firstpage</a>';
?>


