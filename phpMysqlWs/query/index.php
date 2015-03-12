<?php 
//<!-- http://people.cs.clemson.edu/~wei6/index.php -->
session_start();
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

	/* Select queries return a resultset */
	$query = "SELECT MAJOR FROM Applications;";
	$major = array();
    if ($result = $mysqli->query($query)) 
	{
		while ($row = $result->fetch_assoc()) 
		{
			$major[] = $row["MAJOR"];
		}
		$major = array_unique($major);
		sort($major);
		$major = array_values($major);
		/* free result set */
		$result->close();
	}	
?>

<form id="search" action="queryResults.php" method="post">
    StudentID:  <input type="text" name="StudentID" /><br />
   		  GPA:  <input type="text" name="GPA" /><br />
	      ACT:  <input type="text" name="ACT" /><br />
	   County:  <input type="text" name="CountyName" /><br />
	<br />

    <!-- Select a gender from the dropdown form-->
    Select a Gender: <br />
    <select name="Gender">
		<option value="" style="display:none;"></option>
        <option value="F">F</option>
        <option value="M">M</option>
    </select><br />

 	<!-- Select a state origin from the dropdown form-->
    Select a state: <br />
    <select name="StateOrigin">
	<option value="" style="display:none;"></option>
	<?php
		$state = array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL",
						"IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT",
						"NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI",
						"SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY");
		foreach ($state as $state1):?>
	    <option value="<?php echo $state1?>"><?php echo $state1?></option>
		<?php endforeach;?> 
	</select><br />
		
	<!-- Select a major from the dropdown form-->
    Select a major: <br />
    <select name="Major">
    <option value="" style="display:none;"></option> 
	<?php foreach ($major as $major1):?>	
	    <option value="<?php echo $major1?>"> <?php echo $major1?></option>
	<?php endforeach;?> 
	</select><br />
	<br /><br /><br />
    <input type="submit" name="submit" value="Submit!" />
</form>

