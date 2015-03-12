<?php session_start();

/* Get theses variables from another .php file */
$countsOfAttributes = $_SESSION['countsOfAttributes']; 
$delimitertag = $_SESSION['delimitertag']; 
$keywords = $_SESSION['keywords']; 

/* If a user does not select any variable type, exit! */
if(empty($_POST['Tag2'])) exit("You should select a variable type for each attribute, exit");

/* Put the <!selected!> data type of all the attributes into array $allVariableType*/
$allVariableType=array();
foreach ($_POST['Tag2'] as $key =>$value)
{
	if($value == 'int') $allVariableType[] ="INT,";
	elseif($value == 'decimal') $allVariableType[] ="DECIMAL(20,2),";
	else $allVariableType[] = "VARCHAR(50),";
}

/* If the amount of variable type a use selects is smaller than the amount of attributes, exit! */
$countsOfType = count($_POST['Tag2']);
if($countsOfType < $countsOfAttributes)
exit("You should select a variable type for each attribute, exit");

/* Deal with the last element of array $allVariableType */
if($value == 'int') $allVariableType[$countsOfAttributes -1] = "INT";
elseif($value == 'decimal') $allVariableType[$countsOfAttributes -1] = "DECIMAL(20,2)";
else $allVariableType[$countsOfAttributes -1] = "VARCHAR(50)";
echo "All the data types of the attributes have been uploaded successfully.<br>";

/* Combine attributes with their data type into array $compositeOfArray */
$compositeOfArray = array();
for ($i=0; $i<$countsOfAttributes; $i++)
{
	$compositeOfArray[] = $keywords[$i];
	$compositeOfArray[] = $allVariableType[$i];
}

/* Transfer the array $compositeOfArray to string $spaceSeparatedString */
$spaceSeparatedString = implode(" ", $compositeOfArray);

/* Connect to database and enable "LOAD DATA LOCOL INFILE"*/
include_once("dbconnect.inc.php");
$mysqli = mysqli_init();
mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
mysqli_real_connect($mysqli, $host, $user, $password, $database);
if (mysqli_connect_errno()) 
{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$ip  = getenv('REMOTE_ADDR');
print "Here is your IP Address: $ip<br><br>";

/* Create a table Applications */
$query = "DROP TABLE IF EXISTS Applications;";
$mysqli->query($query) or die($mysqli->error.__LINE__);
$query = "CREATE TABLE Applications(ApplicationId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,$spaceSeparatedString);";
$mysqli->query($query) or die($mysqli->error.__LINE__);
echo "The statement of creating table is:<br> $query<br><br>";

/* Load data */
$commaSeparatedkeywords = implode(",", $keywords);
$query ="LOAD DATA LOCAL INFILE '$FileDestination' into TABLE Applications Columns terminated by '$delimitertag' LINES TERMINATED BY '\n' IGNORE 1 lines ($commaSeparatedkeywords);";
$mysqli->query($query) or die($mysqli->error.__LINE__);
echo "The statement of loading data is:<br> $query<br><br>";

/* Update ApplicationId from 0 to counts Of Attributes */
$query ="UPDATE Applications SET ApplicationId = ApplicationId -1";
$mysqli->query($query) or die($mysqli->error.__LINE__);
echo "The data was loaded into database successfully.<br>";

mysqli_close($mysqli);
?>

